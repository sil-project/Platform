<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Manufacturing\Service;

use Sil\Component\Manufacturing\Model\ManufacturingOrder;
use Sil\Component\Stock\Model\OperationType;
use Sil\Component\Stock\Model\Operation;
use Sil\Component\Stock\Model\Location;
use Sil\Component\Stock\Service\OperationServiceInterface;
use Sil\Component\Stock\Service\MovementServiceInterface;
use Sil\Component\Stock\Repository\LocationRepositoryInterface;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class ManufacturingOrderService implements ManufacturingOrderServiceInterface
{
    /**
     * @var OperationServiceInterface
     */
    protected $operationService;

    /**
     * @var MovementServiceInterface
     */
    protected $movementService;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @param OperationService $operationService
     * @param MovementService  $movementService
     */
    public function __construct(
      OperationServiceInterface $operationService,
      MovementServiceInterface $movementService,
      LocationRepositoryInterface $locationRepository)
    {
        $this->operationService = $operationService;
        $this->movementService = $movementService;
        $this->locationRepository = $locationRepository;
    }

    /**
     * @param ManufacturingOrder $order
     */
    public function confirm(ManufacturingOrder $order): void
    {
        $inputOp = $this->createInputOperation($order);
        $order->setInputOperation($inputOp);
        $this->getOperationService()->confirm($inputOp);
        $order->beConfirmed();
    }

    /**
     * @param ManufacturingOrder $order
     */
    public function reserveUnits(ManufacturingOrder $order): void
    {
        $inputOperation = $order->getInputOperation();
        $this->getOperationService()->reserveUnits($inputOperation);

        if ($inputOperation->isAvailable()) {
            $order->beAvailable();
        } else {
            if ($inputOperation->hasReservedStockUnits()) {
                $order->bePartiallyAvailable();
            } else {
                $order->beConfirmed();
            }
        }
    }

    /**
     * @param ManufacturingOrder $order
     */
    public function unreserveUnits(ManufacturingOrder $order): void
    {
        $inputOperation = $order->getInputOperation();
        $this->getOperationService()->unreserveUnits($inputOperation);

        $order->beConfirmed();
    }

    /**
     * @param ManufacturingOrder $order
     */
    public function apply(ManufacturingOrder $order): void
    {
        $inputOperation = $order->getInputOperation();

        $outputOperation = $this->createOutputOperation($order);
        $order->setOutputOperation($outputOperation);

        //apply input first
        $this->getOperationService()->apply($inputOperation);

        //apply output to actualy create the desired StockItem in stock
        $this->getOperationService()->confirm($outputOperation);
        $this->getOperationService()->reserveUnits($outputOperation);
        $this->getOperationService()->apply($outputOperation);

        $order->beDone();
    }

    /**
     * @param ManufacturingOrder $order
     *
     * @return Operation
     */
    protected function createInputOperation(ManufacturingOrder $order): Operation
    {
        $op = $this->getOperationService()->createDraft(OperationType::internalTransfer());

        //@todo problem with $bomLine->getQty()->multipliedBy($order->getQty()->getValue())
        //it works only when the $order qty is a unit
        foreach ($order->getBom()->getLines() as $bomLine) {
            $mv = $this->getMovementService()->createDraft(
                $bomLine->getStockItem(), $bomLine->getQty()->multipliedBy($order->getQty()->getValue()),
                $bomLine->getSrcLocation(), $this->getManufacturingLocation(), $bomLine->getBatch()
            );
            $op->addMovement($mv);
        }

        return $op;
    }

    /**
     * @param ManufacturingOrder $order
     *
     * @return Operation
     */
    protected function createOutputOperation(ManufacturingOrder $order): Operation
    {
        $op = $this->getOperationService()->createDraft(OperationType::internalTransfer());
        $bom = $order->getBom();

        $mv = $this->getMovementService()->createDraft(
              $bom->getStockItem(), $order->getQty(),
              $this->getManufacturingLocation(), $order->getDestLocation(), $order->getBatch()
          );

        $op->addMovement($mv);

        return $op;
    }

    /**
     * @return Location
     */
    protected function getManufacturingLocation(): Location
    {
        //@todo to be fixed
        $virtualLocations = $this->getLocationRepository()->findVirtualLocations();

        return reset($virtualLocations);
    }

    /**
     * @return OperationServiceInterface
     */
    protected function getOperationService(): OperationServiceInterface
    {
        return $this->operationService;
    }

    /**
     * @return MovementServiceInterface
     */
    protected function getMovementService(): MovementServiceInterface
    {
        return $this->movementService;
    }

    /**
     * @return LocationRepositoryInterface
     */
    protected function getLocationRepository(): LocationRepositoryInterface
    {
        return $this->locationRepository;
    }
}
