<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SeedBatchBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Sil\Bundle\SeedBatchBundle\Entity\Plot;

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
    public function addPlot($plot)
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
    public function removePlot($plot)
    {
        return $this->plots->removeElement($plot);
    }

    /**
     * Get plots.
     *
     * @return Collection
     */
    public function getPlots()
    {
        return $this->plots;
    }
}
