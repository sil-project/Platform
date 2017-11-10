<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EmailCRMBundle\Entity\OuterExtension\SilEmailBundle;

use Doctrine\Common\Collections\Collection;

trait EmailExtension
{
    /**
     * @var Collection
     */
    protected $individuals;

    /**
     * @var Collection
     */
    protected $organizations;

    /**
     * @return Collection
     */
    public function getIndividuals()
    {
        return $this->individuals;
    }

    /**
     * @param Collection $individuals
     *
     * @return $this
     */
    public function setIndividuals($individuals)
    {
        $this->individual = $individuals;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getOrganizations()
    {
        return $this->organizations;
    }

    /**
     * @param Collection $organizations
     *
     * @return $this
     */
    public function setOrganizations($organizations)
    {
        $this->organizations = $organizations;

        return $this;
    }
}
