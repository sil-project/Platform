<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Stock\Factory;

use Sil\Component\Stock\Model\Operation;
use Sil\Component\Stock\Model\OperationType;
use Sil\Component\Stock\Model\Location;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
interface OperationFactoryInterface
{
    /**
     * @return Operation
     */
    public function createDraft(OperationType $type, ?Location $srcLocation,
        ?Location $destLocation): Operation;
}
