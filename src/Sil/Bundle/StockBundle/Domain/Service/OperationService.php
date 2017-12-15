<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Service;

use Sil\Bundle\StockBundle\Domain\Repository\OperationRepositoryInterface;
use Sil\Bundle\StockBundle\Domain\Factory\OperationFactoryInterface;
use Sil\Bundle\StockBundle\Domain\Entity\Operation;
use Sil\Bundle\StockBundle\Domain\Entity\OperationType;
use Sil\Bundle\StockBundle\Domain\Entity\Location;
use DomainException;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class OperationService implements OperationServiceInterface
{
    /**
     * @var OperationRepositoryInterface
     */
    private $operationRepository;

    /**
     * @var MovementServiceInterface
     */
    private $movementService;

    /**
     * @var OperationFactoryInterface
     */
    private $operationFactory;

    /**
     * @param OperationRepositoryInterface $operationRepository
     * @param MovementServiceInterface     $movementService
     */
    public function __construct(OperationRepositoryInterface $operationRepository,
        MovementServiceInterface $movementService,
        OperationFactoryInterface $operationFactory)
    {
        $this->operationRepository = $operationRepository;
        $this->movementService = $movementService;
        $this->operationFactory = $operationFactory;
    }

    /**
     * @return Operation
     */
    public function createDraft(OperationType $type, Location $srcLocation, Location $destLocation): Operation
    {
        $op = $this->operationFactory->createDraft($type, $srcLocation, $destLocation);
        $this->operationRepository->add($op);

        return $op;
    }

    public function makeItDraft(Operation $op): void
    {
        if ($op->isInProgress()) {
            throw new DomainException('Operation with reserved units'
                . ' cannot return in the DRAFT state');
        }

        foreach ($op->getMovements() as $mvt) {
            $mvt->beDraft();
        }

        $op->beDraft();
    }

    /**
     * @param Operation $op
     */
    public function confirm(Operation $op): void
    {
        foreach ($op->getMovements() as $mvt) {
            $this->movementService->confirm($mvt);
        }

        $op->beConfirmed();
    }

    /**
     * @param Operation $op
     */
    public function reserveUnits(Operation $op): void
    {
        foreach ($op->getMovements() as $mvt) {
            $this->movementService->reserveUnits($mvt);
        }

        if ($op->isFullyReserved()) {
            $op->beAvailable();
        } else {
            if ($op->hasReservedStockUnits()) {
                $op->bePartiallyAvailable();
            } else {
                $op->beConfirmed();
            }
        }
    }

    /**
     * @param Operation $op
     */
    public function unreserveUnits(Operation $op): void
    {
        foreach ($op->getMovements() as $mvt) {
            $mvt->unreserveAllUnits();
            $mvt->beConfirmed();
        }

        $op->beConfirmed();
    }

    /**
     * @param Operation $op
     */
    public function apply(Operation $op): void
    {
        foreach ($op->getMovements() as $mvt) {
            $this->movementService->apply($mvt);
        }

        $op->beDone();
    }

    /**
     * @param Operation $op
     */
    public function cancel(Operation $op): void
    {
        foreach ($op->getMovements() as $mvt) {
            $this->movementService->cancel($mvt);
        }

        $op->beCancel();
    }
}
