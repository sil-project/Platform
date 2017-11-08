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

namespace Sil\Bundle\SeedBatchBundle\Entity;

use Doctrine\Common\Collections\Collection;

trait HasPlotsTrait
{
    /**
     * @var Collection
     */
    protected $plots;

    /**
     * Add plot.
     *
     * @param Plot $plot
     *
     * @return self
     */
    public function addPlot(Plot $plot)
    {
        $this->plots[] = $plot;

        // We could have used $this->setOwningSideRelation($plot) but Plot#setOrganism method does not exist
        $plot->setProducer($this);

        return $this;
    }

    /**
     * Remove plot.
     *
     * @param Plot $plot
     *
     * @return bool tRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removePlot(Plot $plot)
    {
        return $this->plots->removeElement($plot);
    }

    /**
     * Get plots.
     *
     * @return Collection| Plot[]
     */
    public function getPlots()
    {
        return $this->plots;
    }
}
