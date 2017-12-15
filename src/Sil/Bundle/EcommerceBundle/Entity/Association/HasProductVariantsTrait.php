<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Entity\Association;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sil\Bundle\EcommerceBundle\Entity\ProductVariantInterface;

trait HasProductVariantsTrait
{
    /**
     * @var Collection
     */
    protected $productVariants;

    public function initProductVariants()
    {
        $this->productVariants = new ArrayCollection();
    }

    /**
     * @param ProductVariantInterface $productVariant
     *
     * @return self
     */
    public function addProductVariant(ProductVariantInterface $productVariant)
    {
        $this->productVariants->add($productVariant);

        $this->setOwningSideRelation($productVariant);

        return $this;
    }

    /**
     * @param ProductVariantInterface $productVariant
     *
     * @return self
     */
    public function removeProductVariant(ProductVariantInterface $productVariant)
    {
        $this->productVariants->removeElement($productVariant);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProductVariants()
    {
        return $this->productVariants;
    }
}
