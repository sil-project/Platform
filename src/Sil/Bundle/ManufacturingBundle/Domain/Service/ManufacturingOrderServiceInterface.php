<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ManufacturingBundle\Domain\Service;

use Sil\Bundle\ManufacturingBundle\Domain\Entity\ManufacturingOrder;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
interface ManufacturingOrderServiceInterface
{
    /**
     * @param ManufacturingOrder $order
     */
    public function confirm(ManufacturingOrder $order): void;

    /**
     * @param ManufacturingOrder $order
     */
    public function reserveUnits(ManufacturingOrder $order): void;

    /**
     * @param ManufacturingOrder $order
     */
    public function unreserveUnits(ManufacturingOrder $order): void;

    /**
     * @param ManufacturingOrder $order
     */
    public function apply(ManufacturingOrder $order): void;
}
