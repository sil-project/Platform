<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Entity\Association;

use Doctrine\Common\Collections\Collection;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
trait HasProductsTrait
{
    /**
     * @var Collection
     */
    protected $products;

    /**
     * @param Product $product
     *
     * @return self
     */
    public function addProduct(Product $product)
    {
        $this->products->add($product);

        return $this;
    }

    /**
     * @param Product $product
     *
     * @return self
     */
    public function removeProduct(Product $product)
    {
        $this->products->removeElement($product);

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }
}
