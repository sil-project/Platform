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
use Blast\Bundle\CoreBundle\Doctrine\ORM\OwningSideRelationHandlerTrait;

trait HasProductVariantsTrait
{
    use OwningSideRelationHandlerTrait;

    /**
     * @var Collection
     */
    protected $productVariants;

    public function initProductVariants()
    {
        if ($this->productVariants === null) {
            $this->productVariants = new ArrayCollection();
        }
    }

    /**
     * @param ProductVariantInterface $productVariant
     *
     * @return self
     */
    public function addProductVariant(ProductVariantInterface $productVariant)
    {
        $this->initProductVariants();

        if (!$this->productVariants->contains($productVariant)) {
            $this->productVariants->add($productVariant);
            $this->updateRelation($productVariant, 'add');
        }

        return $this;
    }

    /**
     * @param ProductVariantInterface $productVariant
     *
     * @return self
     */
    public function removeProductVariant(ProductVariantInterface $productVariant)
    {
        $this->initProductVariants();

        if ($this->productVariants->contains($productVariant)) {
            $this->productVariants->removeElement($productVariant);
            $this->updateRelation($productVariant, 'remove');
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProductVariants()
    {
        $this->initProductVariants();

        return $this->productVariants;
    }

    public function setProductVariants(Collection $productVariants)
    {
        $this->initProductVariants();

        $this->productVariants = $productVariants;
    }
}
