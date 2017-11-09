<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Entity;

use Sylius\Component\Core\Model\Product as BaseProduct;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Core\Model\ImageInterface;

class Product extends BaseProduct implements ProductInterface
{
    /**
     * @var Collection|ImageInterface[]
     */
    protected $images;

    protected $taxons = null;

    /**
     * @var Collection|ReviewInterface[]
     */
    protected $reviews;

    public function __construct()
    {
        parent::__construct();
        $this->initProduct();
    }

    public function initProduct()
    {
        $this->images = new ArrayCollection();
        $this->productTaxons = new ArrayCollection();
        $this->taxons = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function setImages(ArrayCollection $images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * alias for SilMediaBundle/CRUDController::handleFiles().
     *
     * @param File $file
     *
     * @return Variety
     */
    public function addLibrinfoFile(ProductImage $file = null)
    {
        if (!$this->images->contains($file)) {
            $this->images->add($file);
        }

        return $this;
    }

    /**
     * alias for SilMediaBundle/CRUDController::handleFiles().
     *
     * @param File $file
     *
     * @return Variety
     */
    public function removeLibrinfoFile(ProductImage $file)
    {
        if ($this->images->contains($file)) {
            $this->images->removeElement($file);
        }

        return $this;
    }

    public function getTaxons(): Collection
    {
        // $this->initTaxons();

        if ($this->taxons === null) {
            return $this->getTaxonsFromProductTaxons();
        }

        return $this->taxons;
    }

    public function setTaxons($taxons)
    {
        $this->initTaxons();

        foreach ($taxons as $taxon) {
            $this->taxons->add($taxon);
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string) parent::__toString();
    }

    private function initTaxons()
    {
        if ($this->taxons === null) {
            $this->taxons = new ArrayCollection();
        }
    }

    public function getTaxonsFromProductTaxons()
    {
        $this->initTaxons();

        $taxons = new ArrayCollection();

        $pTaxons = $this->getProductTaxons();
        foreach ($pTaxons as $pt) {
            $taxons->add($pt->getTaxon());
        }

        return $taxons;
    }
}
