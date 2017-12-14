<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Dashboard;

use Blast\Bundle\DashboardBundle\Block\AbstractBlock;
use Sil\Bundle\EcommerceBundle\Dashboard\Stats\Misc;
use Sil\Bundle\EcommerceBundle\Dashboard\Stats\Sales;
use Sil\Bundle\EcommerceBundle\Dashboard\Stats\OrdersToProcess;

class EcommerceDashboardBlock extends AbstractBlock
{
    /**
     * @var Sales
     */
    private $salesStats;

    /**
     * @var OrdersToProcess
     */
    private $orderToProcessStats;

    /**
     * @var Misc
     */
    private $miscStats;

    public function handleParameters()
    {
        $salesData = $this->salesStats->getData();
        $orderToProcess = $this->orderToProcessStats->getData();
        $miscStats = $this->miscStats->getData();

        $this->templateParameters = [
            'salesAmountData'     => $salesData,
            'lastOrdersToProcess' => $orderToProcess,
            'miscStats'           => $miscStats,
        ];
    }

    /**
     * @param Sales $salesStats
     */
    public function setSalesStats(Sales $salesStats): void
    {
        $this->salesStats = $salesStats;
    }

    /**
     * @param OrdersToProcess $orderToProcessStats
     */
    public function setOrderToProcessStats(OrdersToProcess $orderToProcessStats): void
    {
        $this->orderToProcessStats = $orderToProcessStats;
    }

    /**
     * @param Misc $miscStats
     */
    public function setMiscStats(Misc $miscStats): void
    {
        $this->miscStats = $miscStats;
    }
}
