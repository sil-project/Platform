<?php

declare(strict_types=1);

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Service;

use Sil\Bundle\StockBundle\Domain\Repository\MovementRepositoryInterface;
use Sil\Bundle\StockBundle\Domain\Repository\StockUnitRepositoryInterface;
use Sil\Bundle\StockBundle\Domain\Factory\StockUnitFactoryInterface;
use Sil\Bundle\StockBundle\Domain\Factory\MovementFactoryInterface;
use Sil\Bundle\StockBundle\Domain\Entity\StockItemInterface;
use Sil\Bundle\StockBundle\Domain\Entity\BatchInterface;
use Sil\Bundle\StockBundle\Domain\Entity\StockUnit;
use Sil\Component\Uom\Model\UomQty;
use Sil\Bundle\StockBundle\Domain\Entity\Location;
use Sil\Bundle\StockBundle\Domain\Entity\Movement;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class MovementService implements MovementServiceInterface
{
    /**
     * @var MovementRepositoryInterface
     */
    private $movementRepository;

    /**
     * @var StockUnitRepositoryInterface
     */
    private $stockUnitRepository;

    /**
     * @var MovementFactoryInterface
     */
    private $movementFactory;

    /**
     * @var StockUnitFactoryInterface
     */
    private $stockUnitFactory;

    /**
     * @param MovementRepositoryInterface  $movementRepository
     * @param StockUnitRepositoryInterface $stockUnitRepository
     * @param MovementFactoryInterface     $movementFactory
     * @param StockUnitFactoryInterface    $stockUnitFactory
     */
    public function __construct(MovementRepositoryInterface $movementRepository,
        StockUnitRepositoryInterface $stockUnitRepository,
        MovementFactoryInterface $movementFactory,
        StockUnitFactoryInterface $stockUnitFactory)
    {
        $this->movementRepository = $movementRepository;
        $this->stockUnitRepository = $stockUnitRepository;
        $this->movementFactory = $movementFactory;
        $this->stockUnitFactory = $stockUnitFactory;
    }

    /**
     * @param StockItemInterface  $item
     * @param UomQty              $qty
     * @param Location            $srcLoc
     * @param Location            $destLoc
     * @param BatchInterface|null $batch
     *
     * @return Movement
     */
    public function createDraft(StockItemInterface $item, UomQty $qty,
        Location $srcLoc, Location $destLoc, ?BatchInterface $batch = null): Movement
    {
        $mvt = $this->movementFactory->createDraft($item, $qty);
        $mvt->setSrcLocation($srcLoc);
        $mvt->setDestLocation($destLoc);


        if (null == $batch) {
            $mvt->setBatch($mvt->getBatch());
        }

        $this->movementRepository->add($mvt);

        return $mvt;
    }

    /**
     * @param Movement $mvt
     */
    public function confirm(Movement $mvt): void
    {
        $mvt->beConfirmed();
    }

    /**
     * @param Movement $mvt
     *
     * @throws \DomainException
     */
    public function reserveUnits(Movement $mvt): void
    {
        if (!$mvt->isToDo()) {
            throw new \DomainException('The Movement is not in the right state to be reserved');
        }
        if ($mvt->isAvailable()) {
            return;
        }

        $availableUnits = $this->getAvailableStockUnits($mvt);
        //reserve needed StockUnits and split the last if necessary
        foreach ($availableUnits as $srcUnit) {
            $unit = $srcUnit;

            $remainingQtyToBeRes = $mvt->getRemainingQtyToBeReserved();

            if ($unit->getQty()->isGreaterThan($remainingQtyToBeRes)) {
                $unit = $this->splitAndGetNew($unit, $remainingQtyToBeRes);
                $this->stockUnitRepository->add($unit);
            }

            //reserve the unit
            $mvt->reserve($unit);

            //check if all the qty is reserved
            if ($mvt->isFullyReserved()) {
                $mvt->beAvailable();

                return;
            }
        }
        //all the qty is not reserved yet, a new pass will be necessary
        if ($mvt->hasReservedStockUnits()) {
            $mvt->bePartiallyAvailable();
        } else {
            $mvt->beConfirmed();
        }
    }

    /**
     * @param Movement $mvt
     */
    public function apply(Movement $mvt): void
    {
        $reservedUnits = $mvt->getReservedStockUnits();

        foreach ($reservedUnits as $srcUnit) {
            $mvt->getSrcLocation()->removeStockUnit($srcUnit);

            $destUnit = $this->stockUnitFactory
                ->createFrom($srcUnit, $mvt->getDestLocation());

            $this->stockUnitRepository->remove($srcUnit);
            $this->stockUnitRepository->add($destUnit);
        }

        $mvt->beDone();
    }

    /**
     * @param Movement $mvt
     */
    public function cancel(Movement $mvt): void
    {
        if ($mvt->getSrcLocation()->isManaged()) {
            $mvt->unreserveAllUnits();
        } else {
            //if src location is not managed temporary reserved units have to be removed
            foreach ($mvt->getReservedStockUnits() as $su) {
                $this->stockUnitRepository->remove($su);
            }
        }
        $mvt->beCancel();
    }

    /**
     * @param Movement $mvt
     *
     * @return array
     */
    protected function getAvailableStockUnits(Movement $mvt): array
    {
        //if source location is not managed, just create and return the needed stockUnit to be reserved
        if (!$mvt->getSrcLocation()->isManaged()) {
            $unit = $this->stockUnitFactory->createNew($mvt->getStockItem(),
                $mvt->getQty(), $mvt->getSrcLocation(), $mvt->getBatch());
            $this->stockUnitRepository->add($unit);

            return [$unit];
        }

        return $this->stockUnitRepository->findAvailableForMovementReservation($mvt);
    }

    /**
     * Decrease current qty by $qty, create a new StockUnit of $qty and return it.
     *
     * @param UomQty $qty
     *
     * @return StockUnit
     */
    public function splitAndGetNew(StockUnit $unit, UomQty $qty): StockUnit
    {
        $newQty = $unit->getQty()->decreasedBy($qty);
        $unit->setQty($newQty);

        return $this->stockUnitFactory->createNew(
                $unit->getStockItem(), $qty, $unit->getLocation(),
                $unit->getBatch());
    }

    /**
     * @debug
     *
     * @param Movement $mvt
     *
     * @return string
     */
    public function toString(Movement $mvt): string
    {
        $result = [];
        $result[] = 'created at: ' . $mvt->getCreatedAt()->format('Y-m-d H:i:s');
        $result[] = 'qty to be reserved: ' . $mvt->getQty();
        $result[] = 'remaining qty to be reserved: ' . $mvt->getRemainingQtyToBeReserved();

        foreach ($mvt->getReservedStockUnits() as $unit) {
            $result[] = $unit->getQty();
        }

        return implode("\n", $result);
    }
}
