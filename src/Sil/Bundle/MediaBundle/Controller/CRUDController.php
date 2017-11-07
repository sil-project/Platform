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

namespace Librinfo\MediaBundle\Controller;

use Blast\CoreBundle\Controller\CRUDController as BaseCRUDController;

/**
 * Class CRUDController.
 */
class CRUDController extends BaseCRUDController
{
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * Overrides LibrinfoCore CRUDController and adds uploaded files management.
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
                'SonataAdminBundle:CRUD:select_subclass.html.twig',
                array(
                    'base_template' => $this->getBaseTemplate(),
                    'admin'         => $this->admin,
                    'action'        => 'create',
                ),
                null,
                $request
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

        $this->handleFiles($object);

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

                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson(array(
                                    'result'   => 'ok',
                                    'objectId' => $this->admin->getNormalizedIdentifier($object),
                                        ), 200, array());
                    }

                    $this->addFlash(
                        'sonata_flash_success',
                        $this->admin->trans(
                            'flash_create_success',
                            array('%name%' => $this->escapeHtml($this->admin->toString($object))),
                            'SonataAdminBundle'
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
                        'sonata_flash_error',
                        $this->admin->trans(
                            'flash_create_error',
                            array('%name%' => $this->escapeHtml($this->admin->toString($object))),
                            'SonataAdminBundle'
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

        return $this->render($this->admin->getTemplate($templateKey), array(
                    'action' => 'create',
                    'form'   => $view,
                    'object' => $object,
                        ), null);
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

        /** @var $form Form */
        $form = $this->admin->getForm();
        $form->setData($object);
        $form->handleRequest($request);

        $this->handleFiles($object);

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

                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson(array(
                                    'result'     => 'ok',
                                    'objectId'   => $this->admin->getNormalizedIdentifier($object),
                                    'objectName' => $this->escapeHtml($this->admin->toString($object)),
                                        ), 200, array());
                    }

                    $this->addFlash(
                        'sonata_flash_success',
                        $this->admin->trans(
                            'flash_edit_success',
                            array('%name%' => $this->escapeHtml($this->admin->toString($object))),
                            'SonataAdminBundle'
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
                        'sonata_flash_error',
                        $this->admin->trans(
                            'flash_edit_error',
                            array('%name%' => $this->escapeHtml($this->admin->toString($object))),
                            'SonataAdminBundle'
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

        return $this->render($this->admin->getTemplate($templateKey), array(
                    'action' => 'edit',
                    'form'   => $view,
                    'object' => $object,
                        ), null);
    }

    /**
     * Duplicate action.
     *
     * @return response
     */
    public function duplicateAction()
    {
        $id = $this->getRequest()->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);
        $new = clone $object;

        $this->duplicateFiles($object, $new);

        $preResponse = $this->preDuplicate($new);
        if ($preResponse !== null) {
            return $preResponse;
        }

        return $this->createAction($new);
    }

    /**
     * Binds the uploaded file to its owner on creation.
     *
     * @param object $object
     * @param array  $ids
     */
    protected function handleFiles($object)
    {
        $request = $this->getRequest();

        $rc = new \ReflectionClass($object);
        $className = $rc->getShortName();

        $repo = $this->manager->getRepository('LibrinfoMediaBundle:File');

        if ($remove = $request->get('remove_files')) {
            foreach ($remove as $key => $id) {
                $file = $repo->find($id);

                if ($file) {
                    if (method_exists($object, 'removeLibrinfoFile')) {
                        $object->removeLibrinfoFile($file);
                        $this->manager->remove($file);
                    } elseif (method_exists($object, 'setLibrinfoFile')) {
                        $object->setLibrinfoFile();
                        $this->manager->remove($file);
                    } else {
                        throw new \Exception('You must define ' . $className . '::removeLibrinfoFile() method or ' . $className . '::setFile() in case of a one to one');
                    }
                }
            }
        }

        if ($ids = $request->get('add_files')) {
            foreach ($ids as $key => $id) {
                $file = $repo->find($id);

                if ($file) {
                    if (method_exists($object, 'addLibrinfoFile')) {
                        $object->addLibrinfoFile($file);
                        $file->setOwned(true);
                    } elseif (method_exists($object, 'setLibrinfoFile')) {
                        $object->setLibrinfoFile($file);
                        $file->setOwned(true);
                    } else {
                        throw new \Exception('You must define ' . $className . '::addLibrinfoFile() method or ' . $className . '::setLibrinfoFile() in case of a one to one');
                    }
                }
            }
        }
    }

    protected function duplicateFiles($object, $clone)
    {
        $files = [];
        $manager = $this->getDoctrine()->getManager();

        if (method_exists($object, 'getLibrinfoFiles')) {
            $files = $object->getLibrinfoFiles();
        } elseif (method_exists($object, 'getLibrinfoFile')) {
            $files = [$object->getLibrinfoFile()];
        }

        foreach ($files as $file) {
            $new = clone $file;

            if (method_exists($clone, 'addLibrinfoFile')) {
                $clone->addLibrinfoFile($new);
            } elseif (method_exists($new, 'setLibrinfoFile')) {
                $clone->setLibrinfoFile($new);
            }

            $manager->persist($new);
        }

        $manager->flush();
    }
}
