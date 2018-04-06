<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Stock\Model;

use Sil\Component\Uom\Model\Uom;
use Blast\Component\Resource\Model\ResourceInterface;
use Blast\Component\Code\Model\CodeInterface;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
interface StockItemInterface extends ResourceInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return CodeInterface
     */
    public function getCode(): CodeInterface;

    /**
     * @return Uom
     */
    public function getUom(): ?Uom;

    /**
     * @return OutputStrategy
     */
    public function getOutputStrategy(): ?OutputStrategy;
}
