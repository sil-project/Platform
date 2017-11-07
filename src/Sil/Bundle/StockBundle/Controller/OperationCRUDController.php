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

namespace Sil\Bundle\StockBundle\Controller;

use Blast\Bundle\CoreBundle\Controller\CRUDController;
use Sil\Bundle\StockBundle\Domain\Entity\Operation;
use Sil\Bundle\StockBundle\Domain\Service\OperationServiceInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Sil\Bundle\StockBundle\Domain\Entity\OperationType;

/**
 * Description of MovementController.
 *
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class OperationCRUDController extends CRUDController
{
    public function createByTypeAction()
    {
        $request = $this->getRequest();
        // the key used to lookup the template
        $templateKey = 'edit';

        $this->admin->checkAccess('create');
       
        $opType = OperationType::{$request->get('type')}();
        $class = new \ReflectionClass($this->admin->hasActiveSubClass() ? $this->admin->getActiveSubClass() : $this->admin->getClass());

        if ($class->isAbstract()) {
            return $this->render(
                    'SonataAdminBundle:CRUD:select_subclass.html.twig',
                    array(
                        'base_template' => $this->getBaseTemplate(),
                        'admin'         => $this->admin,
                        'action'        => 'create',
                    ), null, $request
            );
        }

        $object = $this->admin->getNewInstance();
        $object->setType($opType);

        $preResponse = $this->preCreate($request, $object);
        if ($preResponse !== null) {
            return $preResponse;
        }

        $this->admin->setSubject($object);

        /* @var $form Form */
        $form = $this->admin->getForm();
        $form->setData($object);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //TODO: remove this check for 4.0
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

        return $this->render($this->admin->getTemplate($templateKey),
                array(
                    'action' => 'create',
                    'form'   => $view,
                    'object' => $object,
                ), null);
    }

    public function preEdit(Request $request, $operation)
    {
        $this->getOperationService()->makeItDraft($operation);
        $this->admin->update($operation);
        parent::preEdit($request, $operation);
    }

    /**
     * @var OperationServiceInterface
     */
    protected $operationService;

    public function confirmAction()
    {
        /* @var $operation Operation */
        $operation = $this->admin->getSubject();

        $this->getOperationService()->confirm($operation);
        $this->admin->update($operation);

        return $this->redirectTo($operation);
    }

    public function reserveAction()
    {
        /* @var $operation Operation */
        $operation = $this->admin->getSubject();

        $this->getOperationService()->reserveUnits($operation);
        $this->admin->update($operation);

        //nothing has been reserved because no stock available
        if ($operation->isConfirmed()) {
            $this->addFlash('sonata_flash_info',
                $this->trans(
                    'sil.stock.operation.message.no_available_stock_for_reservation'));
        }

        return $this->redirectTo($operation);
    }

    public function unreserveAction()
    {
        /* @var $operation Operation */
        $operation = $this->admin->getSubject();

        $this->getOperationService()->unreserveUnits($operation);
        $this->admin->update($operation);

        return $this->redirectTo($operation);
    }

    public function cancelAction()
    {
        /* @var $operation Operation */
        $operation = $this->admin->getSubject();

        $this->getOperationService()->cancel($operation);
        $this->admin->update($operation);

        return $this->redirectTo($operation);
    }

    public function applyAction()
    {
        /* @var $operation Operation */
        $operation = $this->admin->getSubject();

        $this->getOperationService()->apply($operation);
        $this->admin->update($operation);

        return $this->redirectTo($operation);
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

        if (null !== $request->get('btn_update_and_list')) {
            $url = $this->admin->generateUrl('list');
        }
        if (null !== $request->get('btn_create_and_list')) {
            $url = $this->admin->generateUrl('list');
        }

        if (null !== $request->get('btn_create_and_create')) {
            $params = array();
            if ($this->admin->hasActiveSubClass()) {
                $params['subclass'] = $request->get('subclass');
            }
            $url = $this->admin->generateUrl('create', $params);
        }

        if ($this->getRestMethod() === 'DELETE') {
            $url = $this->admin->generateUrl('list');
        }

        if (!$url) {
            foreach (array('show', 'edit') as $route) {
                if ($this->admin->hasRoute($route) && $this->admin->hasAccess($route,
                        $object)) {
                    $url = $this->admin->generateObjectUrl($route, $object);

                    break;
                }
            }
        }

        if (!$url) {
            $url = $this->admin->generateUrl('list');
        }

        return new RedirectResponse($url);
    }

    /**
     * @return OperationServiceInterface
     */
    public function getOperationService(): OperationServiceInterface
    {
        return $this->operationService;
    }

    /**
     * @param OperationServiceInterface $operationService
     */
    public function setOperationService(OperationServiceInterface $operationService)
    {
        $this->operationService = $operationService;
    }
}
