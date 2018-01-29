<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ManufacturingBundle\Controller;

use Blast\Bundle\CoreBundle\Controller\CRUDController;
use Sil\Bundle\ManufacturingBundle\Domain\Entity\ManufacturingOrder;
use Sil\Bundle\ManufacturingBundle\Domain\Service\ManufacturingOrderServiceInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class ManufacturingOrderCRUDController extends CRUDController
{
    public function preEdit(Request $request, $mOrder)
    {
        $this->getManufacturingOrderService()->makeItDraft($mOrder);
        $this->admin->update($mOrder);
        parent::preEdit($request, $mOrder);
    }

    /**
     * @var OperationServiceInterface
     */
    protected $operationService;

    public function confirmAction()
    {
        /* @var $operation ManufacturingOrder */
        $mOrder = $this->admin->getSubject();

        $this->getManufacturingOrderService()->confirm($mOrder);
        $this->admin->update($mOrder);

        return $this->redirectTo($mOrder);
    }

    public function reserveAction()
    {
        /* @var $operation ManufacturingOrder */
        $mOrder = $this->admin->getSubject();

        $this->getManufacturingOrderService()->reserveUnits($mOrder);
        $this->admin->update($mOrder);

        //nothing has been reserved because no stock available
        if ($mOrder->isConfirmed()) {
            $this->addFlash('sonata_flash_info',
                $this->trans(
                    'sil.stock.operation.message.no_available_stock_for_reservation'));
        }

        return $this->redirectTo($mOrder);
    }

    public function unreserveAction()
    {
        /* @var $operation ManufacturingOrder */
        $mOrder = $this->admin->getSubject();

        $this->getManufacturingOrderService()->unreserveUnits($mOrder);
        $this->admin->update($mOrder);

        return $this->redirectTo($mOrder);
    }

    public function cancelAction()
    {
        /* @var $operation ManufacturingOrder */
        $mOrder = $this->admin->getSubject();

        $this->getManufacturingOrderService()->cancel($mOrder);
        $this->admin->update($mOrder);

        return $this->redirectTo($mOrder);
    }

    public function applyAction()
    {
        /* @var $operation ManufacturingOrder */
        $mOrder = $this->admin->getSubject();

        $this->getManufacturingOrderService()->apply($mOrder);
        $this->admin->update($mOrder);

        return $this->redirectTo($mOrder);
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
     * @return ManufacturingOrderServiceInterface
     */
    public function getManufacturingOrderService(): ManufacturingOrderServiceInterface
    {
        return $this->manufacturingOrderService;
    }

    /**
     * @param ManufacturingOrderServiceInterface $moService
     */
    public function setManufacturingOrderService(ManufacturingOrderServiceInterface $moService)
    {
        $this->manufacturingOrderService = $moService;
    }
}
