<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Entity\SilEcommerceBundle;

use Doctrine\Common\Collections\ArrayCollection;
use Sil\Bundle\StockBundle\Domain\Entity\StockItemInterface;
use Sil\Bundle\SeedBatchBundle\Entity\Association\HasSeedBatchesTrait;
use Sil\Bundle\EcommerceBundle\Entity\ProductVariant as BaseProductVariant;

class ProductVariant extends BaseProductVariant
{
    use HasSeedBatchesTrait;

    /**
     * @var StockItemInterface
     */
    protected $stockItem;

    /**
     * @param string $optionCode
     *
     * @return ArrayCollection
     */
    public function getOptionValuesByCode($optionCode)
    {
        return $this->optionValues->filter(
            function ($optionValue) use ($optionCode) {
                return $optionValue->getOption()->getCode() == $optionCode;
            }
        );
    }

    /**
     * @return ProductOptionValue|null
     */
    public function getPackaging()
    {
        $optionValue = $this->getOptionValuesByCode(Product::$PACKAGING_OPTION_CODE)->first();

        return $optionValue ? $optionValue : null;
    }

    /**
     * @param ProductOptionValue $packaging
     *
     * @return self
     */
    public function setPackaging(ProductOptionValue $packaging)
    {
        if ($packaging->getOptionCode() != Product::$PACKAGING_OPTION_CODE) {
            throw new \UnexpectedValueException(sprintf('The argument passed to Product::setPackaging should have optionCode = %s', Product::$PACKAGING_OPTION_CODE));
        }
        foreach ($this->getOptionValuesByCode(Product::$PACKAGING_OPTION_CODE) as $optionValue) {
            $this->removeOptionValue($optionValue);
        }
        $this->addOptionValue($packaging);

        return $this;
    }

    public function initCollections()
    {
        $this->seedBatches = new ArrayCollection();
    }

    /**
     * @return StockItemInterface
     */
    public function getStockItem(): StockItemInterface
    {
        return $this->stockItem;
    }

    /**
     * @param StockItemInterface $stockItem
     */
    public function setStockItem(StockItemInterface $stockItem): void
    {
        $this->stockItem = $stockItem;
    }
}
