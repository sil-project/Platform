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

use Sil\Bundle\StockBundle\Domain\Entity\Operation;
use Sil\Bundle\StockBundle\Domain\Entity\Location;
use Sil\Bundle\StockBundle\Domain\Entity\OperationType;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
interface OperationServiceInterface
{
    /**
     * @return Operation
     */
    public function createDraft(OperationType $type, Location $srcLocation, Location $destLocation): Operation;

    /**
     * @param Operation $op
     */
    public function confirm(Operation $op): void;

    /**
     * @param Operation $op
     */
    public function reserveUnits(Operation $op): void;

    /**
     * @param Operation $op
     */
    public function apply(Operation $op): void;

    /**
     * @param Operation $op
     */
    public function cancel(Operation $op): void;
}
