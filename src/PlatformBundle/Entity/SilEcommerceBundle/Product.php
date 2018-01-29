<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PlatformBundle\Entity\SilEcommerceBundle;

use Sil\Bundle\EcommerceBundle\Entity\Product as BaseProduct;
use Sil\Component\Stock\Model\StockItemInterface;
use Sil\Component\Stock\Model\OutputStrategy;
use Sil\Component\Uom\Model\Uom;

class Product extends BaseProduct implements StockItemInterface
{
    /**
     * @var Uom
     */
    protected $uom;

    /**
     * @var OutputStrategy
     */
    protected $outputStrategy;

    /**
     * @return Uom
     */
    public function getUom(): ?Uom
    {
        return $this->uom;
    }

    /**
     * @return OutputStrategy
     */
    public function getOutputStrategy(): ?OutputStrategy
    {
        return $this->outputStrategy;
    }

    /**
     * @param Uom $uom
     */
    public function setUom(Uom $uom): void
    {
        $this->uom = $uom;
    }

    /**
     * @param OutputStrategy $outputStrategy
     */
    public function setOutputStrategy(OutputStrategy $outputStrategy): void
    {
        $this->outputStrategy = $outputStrategy;
    }
}
