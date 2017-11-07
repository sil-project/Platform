<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EmailBundle\Controller;

use Sil\Bundle\MediaBundle\Controller\CRUDController as BaseCRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CRUDController extends BaseCRUDController
{
    /**
     * Sends the email and redirects to list view keeping filter parameters.
     *
     * @return RedirectResponse
     */
    public function sendAction(Request $request)
    {
        $id = $request->get('id');
        $email = $this->admin->getObject($id);

        //prevent resending of an email
        if ($email->getSent()) {
            $this->addFlash('sonata_flash_error', 'Message ' . $id . ' déjà envoyé');

            if ($this->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'status' => 'NOK',
                    'sent'   => true,
                    'error'  => 'librinfo_email.error.email_already_sent',
                ));
            }

            return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
        }

        $sender = $this->get('librinfo_email.sender');
        $error = false;

        try {
            $nbSent = $sender->send($email);
        } catch (\Exception $exc) {
            $error = $exc->getMessage();

            if ($this->isXmlHttpRequest()) {
                return new JsonResponse(array(
                    'status' => 'NOK',
                    'sent'   => false,
                    'error'  => $error,
                ));
            }
        }

        if ($error) {
            $this->addFlash(
                'sonata_flash_error',
                $this->get('translator')->trans('librinfo_email.flash.message_not_sent') . ': ' . $error
            );
        } else {
            $this->addFlash(
                'sonata_flash_success',
                $this->get('translator')->trans('librinfo_email.flash.message_sent')
            );
        }

        if ($this->isXmlHttpRequest()) {
            return new JsonResponse(array(
                'status' => 'OK',
                'sent'   => true,
            ));
        }

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

    /**
     * Adds tracking data to show view.
     *
     * @param Request $request
     * @param Email   $object
     *
     * @return Response
     */
    protected function preShow(Request $request, $object)
    {
        $twigArray = [
            'action'   => 'show',
            'object'   => $object,
            'elements' => $this->admin->getShow(),
        ];

        $this->admin->setSubject($object);

        if ($object->getTracking()) {
            $twigArray['stats'] = $this->get('librinfo_email.stats')->getStats($object);
        }

        return $this->render($this->admin->getTemplate('show'), $twigArray, null);
    }

    /**
     * Overrides SonataAdminBundle CRUDController.
     *
     * @param Email $object
     *
     * @return Response
     */
    public function createAction($object = null)
    {
        $request = $this->getRequest();
        $this->manager = $this->getDoctrine()->getManager();
        // the key used to lookup the template
        $templateKey = 'edit';

        $this->admin->checkAccess('create');

        $class = new \ReflectionClass($this->admin->hasActiveSubClass() ? $this->admin->getActiveSubClass() : $this->admin->getClass());

        if ($class->isAbstract()) {
            return $this->render(
                            'SonataAdminBundle:CRUD:select_subclass.html.twig', array(
                        'base_template' => $this->getBaseTemplate(),
                        'admin'         => $this->admin,
                        'action'        => 'create',
                            ), null, $request
            );
        }

        $object = $object ? $object : $this->admin->getNewInstance();

        $preResponse = $this->preCreate($request, $object);
        if ($preResponse !== null) {
            return $preResponse;
        }

        $this->admin->setSubject($object);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $this->admin->getForm();
        $form->setData($object);
        $form->handleRequest($request);

        $this->handleFiles($object, $request->get('file_ids'));

        if ($form->isSubmitted()) {
            //TODO: remove this check for 3.0
            if (method_exists($this->admin, 'preValidate')) {
                $this->admin->preValidate($object);
            }
            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode($request) || $this->isPreviewApproved($request))) {
                $this->admin->checkAccess('create', $object);

                try {
                    $object = $this->admin->create($object);

                    //set email isTest to true as the checkbox is disabled in create action
                    $object->setIsTest(true);

                    $this->handleTest($object);

                    $this->handleTemplate($object);

                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson(array(
                                    'result'   => 'ok',
                                    'objectId' => $this->admin->getNormalizedIdentifier($object),
                                        ), 200, array());
                    }

                    $this->addFlash(
                            'sonata_flash_success', $this->admin->trans(
                                    'flash_create_success', array('%name%' => $this->escapeHtml($this->admin->toString($object))), 'SonataAdminBundle'
                            )
                    );

                    // redirect to edit mode
                    return $this->redirectTo($object);
                } catch (ModelManagerException $e) {
                    $this->handleModelManagerException($e);

                    $isFormValid = false;
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash(
                            'sonata_flash_error', $this->admin->trans(
                                    'flash_create_error', array('%name%' => $this->escapeHtml($this->admin->toString($object))), 'SonataAdminBundle'
                            )
                    );
                }
            } elseif ($this->isPreviewRequested()) {
                // pick the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->defineFormTheme($view, $this->admin->getFormTheme());

        return $this->render(
            $this->admin->getTemplate($templateKey),
            array(
                'action' => 'create',
                'form'   => $view,
                'object' => $object,
                ),
            null
        );
    }

    /**
     * Overrides SonataAdminBundle CRUDController.
     *
     * @param type $id
     *
     * @return type
     *
     * @throws type
     */
    public function editAction($id = null)
    {
        $request = $this->getRequest();
        $this->manager = $this->getDoctrine()->getManager();
        // the key used to lookup the template
        $templateKey = 'edit';

        $id = $request->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
        }

        $this->admin->checkAccess('edit', $object);

        $preResponse = $this->preEdit($request, $object);
        if ($preResponse !== null) {
            return $preResponse;
        }

        $this->admin->setSubject($object);

        /**
         * @var Form
         */
        $form = $this->admin->getForm();
        $form->setData($object);
        $form->handleRequest($request);

        $this->handleFiles($object, $request->get('file_ids'));

        if ($form->isSubmitted()) {
            //TODO: remove this check for 3.0
            if (method_exists($this->admin, 'preValidate')) {
                $this->admin->preValidate($object);
            }
            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                try {
                    $object = $this->admin->update($object);
                    /*                     * ********************************************************************************************** */
                    $this->handleTest($object);

                    $this->handleTemplate($object);
                    /*                     * **************************************************************************************** */
                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson(array(
                                    'result'     => 'ok',
                                    'objectId'   => $this->admin->getNormalizedIdentifier($object),
                                    'objectName' => $this->escapeHtml($this->admin->toString($object)),
                                        ), 200, array());
                    }

                    $this->addFlash(
                            'sonata_flash_success', $this->admin->trans(
                                    'flash_edit_success', array('%name%' => $this->escapeHtml($this->admin->toString($object))), 'SonataAdminBundle'
                            )
                    );

                    // redirect to edit mode
                    return $this->redirectTo($object);
                } catch (ModelManagerException $e) {
                    $this->handleModelManagerException($e);

                    $isFormValid = false;
                } catch (LockException $e) {
                    $this->addFlash('sonata_flash_error', $this->admin->trans('flash_lock_error', array(
                                '%name%'       => $this->escapeHtml($this->admin->toString($object)),
                                '%link_start%' => '<a href="' . $this->admin->generateObjectUrl('edit', $object) . '">',
                                '%link_end%'   => '</a>',
                                    ), 'SonataAdminBundle'));
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash(
                            'sonata_flash_error', $this->admin->trans(
                                    'flash_edit_error', array('%name%' => $this->escapeHtml($this->admin->toString($object))), 'SonataAdminBundle'
                            )
                    );
                }
            } elseif ($this->isPreviewRequested()) {
                // enable the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->defineFormTheme($view, $this->admin->getFormTheme());

        return $this->render(
            $this->admin->getTemplate($templateKey),
            [
                'action' => 'edit',
                'form'   => $view,
                'object' => $object,
            ]
        );
    }

    /**
     * Handles sending of the test Email.
     *
     * @param Email $email
     */
    protected function handleTest($email)
    {
        if ($email->getIsTest() && $email->getTestAddress()) {
            $this->get('librinfo_email.sender')->send($email);
        }
    }

    /**
     * Handle creation of the template from email content.
     *
     * @param Email $email
     */
    protected function handleTemplate($email)
    {
        if ($email->getIsTemplate() && $email->getNewTemplateName()) {
            $template = new \Sil\Bundle\EmailBundle\Entity\EmailTemplate();
            $template->setContent($email->getContent());
            $template->setName($email->getNewTemplateName());
            $this->manager->persist($template);
            $this->manager->flush();
        }
    }

    public function listAction()
    {
        $request = $this->getRequest();

        $this->admin->checkAccess('list');

        $preResponse = $this->preList($request);
        if ($preResponse !== null) {
            return $preResponse;
        }

        if ($listMode = $request->get('_list_mode')) {
            $this->admin->setListMode($listMode);
        }

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        $this->defineFormTheme($formView, $this->admin->getFilterTheme());

        return $this->render(
            $this->admin->getTemplate('list'),
            array(
                'action'     => 'list',
                'form'       => $formView,
                'datagrid'   => $datagrid,
                'csrf_token' => $this->getCsrfToken('sonata.batch'),
            ),
            null,
            $request
        );
    }

    /**
     * Redirect the user depend on this choice.
     *
     * @param object $object
     *
     * @return RedirectResponse
     */
    protected function redirectTo($object)
    {
        $request = $this->getRequest();
        $url = false;
        $from_admin = $request->get('from_admin'); // admin code
        $from_id = $request->get('from_id');

        if ($from_admin !== null && $from_id !== null) {
            $admin = $this->get($from_admin);
            $from_object = $admin->getObject($from_id);

            if ($admin->isGranted('SHOW', $from_object)) {
                $url = $admin->generateObjectUrl('show', $from_object);
            }
        }

        if ($url) {
            return new RedirectResponse($url);
        }

        return parent::redirectTo($object);
    }
}
