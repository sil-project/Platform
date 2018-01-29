<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Admin;

use Blast\Bundle\ResourceBundle\Sonata\Admin\ResourceAdmin;
use Sil\Component\Stock\Generator\MovementCodeGeneratorInterface;
use Sil\Component\Stock\Query\StockItemQueriesInterface;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class MovementAdmin extends ResourceAdmin
{
    protected $baseRouteName = 'admin_stock_movement';
    protected $baseRoutePattern = 'stock/movement';

    /**
     * @var MovementCodeGeneratorInterface
     */
    protected $movementCodeGenerator;

    public function prePersist($object)
    {
        $this->preUpdate($object);
    }

    public function preUpdate($object)
    {
        $code = $this->getMovementCodeGenerator()
            ->generate($object->getStockItem(), $object->getQty());
        $object->setCode($code);

        parent::preUpdate($object);
    }

    public function getReservedQty($mvt)
    {
        return $this->getStockItemQueries()->getReservedQtyByMovement($mvt->getStockItem(),
                $mvt);
    }

    public function getMovementCodeGenerator(): MovementCodeGeneratorInterface
    {
        return $this->movementCodeGenerator;
    }

    public function setMovementCodeGenerator(MovementCodeGeneratorInterface $codeGenerator)
    {
        $this->movementCodeGenerator = $codeGenerator;
    }

    public function getStockItemQueries(): StockItemQueriesInterface
    {
        return $this->stockItemQueries;
    }

    public function setStockItemQueries(StockItemQueriesInterface $stockItemQueries)
    {
        $this->stockItemQueries = $stockItemQueries;
    }

    public function toString($movement)
    {
        return sprintf('[%s]', $movement->getCode());
    }
}
