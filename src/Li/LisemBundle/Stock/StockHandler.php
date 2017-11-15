<?php

/*
 * This file is part of the Lisem Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Stock;

use Sil\Bundle\EcommerceBundle\Entity\OrderInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;
use Sil\Bundle\StockBundle\Domain\Service\OperationService;
use Sil\Bundle\StockBundle\Domain\Service\MovementService;
use Sil\Bundle\StockBundle\Domain\Entity\UomQty;
use Sil\Bundle\StockBundle\Domain\Entity\Operation;

class StockHandler
{
    /**
     * @var OperationService
     */
    private $stockOperationHandler;

    /**
     * @var MovementService
     */
    private $stockMovementHandler;

    public function handleOrder(OrderInterface $order, $targetState)
    {
        // $operation = $order->getStockOperation();

        // ALL thoses actions must check if items are already manipulated or not before applying their logic.

        switch ($targetState) {
            case OrderInterface::STATE_NEW:

                // RESERVE order items stock items

                // $operation = $this->stockOperationHandler->createDraft();

                // Set locations : source and dest

                // $this->createMovementsForOperationAndOrder($operation, $order);

                break;

            case OrderInterface::STATE_FULFILLED:

                // APPLY order items stock items reservation

                // Do nothing because shipment has already applyed items reservation

                break;

            case OrderInterface::STATE_CANCELLED:

                // RELEASE order items stock items reservation

                break;
        }
    }

    public function handleShipment(ShipmentInterface $shipment, $targetState)
    {
        // ALL thoses actions must check if items are already manipulated or not before applying their logic.

        switch ($targetState) {
            case ShipmentInterface::STATE_READY:

                // RESERVE order items stock items

                // Do nothing because order has already reserved items

                break;

            case ShipmentInterface::STATE_SHIPPED:

                // APPLY order items stock items reservation

                break;

            case ShipmentInterface::STATE_CANCELLED:

                // RELEASE order items stock items reservation

                break;
        }
    }

    private function createMovementsForOperationAndOrder(Operation $operation, OrderInterface $order)
    {
        foreach ($order->getOrderItems() as $orderItem) {
            $stockItem = $orderItem->getStockItem();

            if ($orderItem->isBulk()) {
                $uom = null; //@TODO: get µgram uom
            } else {
                $uom = $stockItem->getUom(); // get stock item default unit
            }

            $qty = new UomQty($uom, $orderItem->getQuantity());

            $movement = $this->stockMovementHandler->createDraft(
                $stockItem,
                $qty,
                $operation->getSrcLocation(),
                $operation->getDestLocation()
            );
        }
    }

    /**
     * @param OperationService $stockOperationHandler
     */
    public function setStockOperationHandler(OperationService $stockOperationHandler): void
    {
        $this->stockOperationHandler = $stockOperationHandler;
    }

    /**
     * @param MovementService $stockMovementHandler
     */
    public function setStockMovementHandler(MovementService $stockMovementHandler): void
    {
        $this->stockMovementHandler = $stockMovementHandler;
    }
}
