<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Stock\Service;

use Sil\Component\Stock\Repository\StockUnitRepositoryInterface;
use Sil\Component\Stock\Model\StockItemInterface;
use Sil\Component\Uom\Model\Uom;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class UomService implements UomServiceInterface
{
    /**
     * @var StockUnitRepositoryInterface
     */
    protected $stockUnitRepository;

    /**
     * @param StockUnitRepositoryInterface $stockUnitRepo
     */
    public function __construct(StockUnitRepositoryInterface $stockUnitRepo)
    {
        $this->stockUnitRepository = $stockUnitRepo;
    }

    /**
     * @param StockItemInterface $item
     * @param Uom                $newUom
     */
    public function updateUomForStockItem(StockItemInterface $item, Uom $newUom): void
    {
        $stockUnits = $this->stockUnitRepository->findByStockItem($item);

        foreach ($stockUnits as $sUnit) {
            $sUnit->setQty($sUnit->getQty()->convertTo($newUom));
        }

        $item->setUom($newUom);
    }
}
