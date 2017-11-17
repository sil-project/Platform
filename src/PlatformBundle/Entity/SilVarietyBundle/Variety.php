<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PlatformBundle\Entity\SilVarietyBundle;

use Sil\Bundle\VarietyBundle\Entity\Variety as BaseVariety;
use Sil\Bundle\SonataSyliusUserBundle\Entity\Traits\Traceable;
use Sil\Bundle\SeedBatchBundle\Entity\Association\HasSeedBatchesTrait;
use Sil\Bundle\EcommerceBundle\Entity\Association\HasProductsTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Sil\Bundle\EcommerceBundle\Entity\ProductOption;
use PlatformBundle\Entity\ChannelDescription;

class Variety extends BaseVariety
{
    use Traceable,
        HasSeedBatchesTrait,
        HasProductsTrait;

    /**
     * @var Collection|ChannelDescription[]
     */
    protected $channelDescriptions;

    /**
     * @var Collection|ProductOption[]
     */
    protected $packagings;

    public function __construct()
    {
        parent::__construct();

        $this->channelDescriptions = new ArrayCollection();
        $this->packagings = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->seedBatches = new ArrayCollection();
    }

    /**
     * @param ChannelDescription $channelDescription
     */
    public function addChannelDescription(ChannelDescription $channelDescription)
    {
        $this->channelDescriptions->add($channelDescription);
    }

    /**
     * @param ChannelDescription $channelDescription
     */
    public function removeChannelDescription(ChannelDescription $channelDescription)
    {
        $this->channelDescriptions->removeElement($channelDescription);
    }

    /**
     * @return Collection|ChannelDescription[]
     */
    public function getChannelDescriptions()
    {
        return $this->channelDescriptions;
    }

    /**
     * @param ProductOption $packaging
     */
    public function addPackaging(ProductOption $packaging)
    {
        $this->packagings->add($packaging);
    }

    /**
     * @param ProductOption $packaging
     */
    public function removePackaging(ProductOption $packaging)
    {
        $this->packagings->removeElement($packaging);
    }

    /**
     * @return Collection|ProductOption[]
     */
    public function getPackagings()
    {
        return $this->packagings;
    }

    /**
     * @param Collection|ProductOption[]
     */
    public function setPackagings(Collection $packagings)
    {
        $this->packagings = $packagings;
    }
}
