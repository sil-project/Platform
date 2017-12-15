<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Dashboard\Stats;

class Sales extends AbstractStats
{
    /**
     * @var RawQueries
     */
    private $rawQueries;

    public function getData(array $parameters = []): array
    {
        return $this->rawQueries->getSalesForRunningYear();
    }

    /**
     * @param RawQueries $rawQueries
     */
    public function setRawQueries(RawQueries $rawQueries): void
    {
        $this->rawQueries = $rawQueries;
    }
}
