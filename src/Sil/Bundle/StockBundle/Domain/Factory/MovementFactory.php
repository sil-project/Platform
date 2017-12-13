<?php

declare(strict_types=1);

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Factory;

use Sil\Bundle\StockBundle\Domain\Entity\Movement;
use Sil\Bundle\StockBundle\Domain\Generator\MovementCodeGeneratorInterface;
use Sil\Bundle\StockBundle\Domain\Entity\StockItemInterface;
use Sil\Component\Uom\Model\UomQty;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class MovementFactory implements MovementFactoryInterface
{
    /**
     * @var MovementCodeGeneratorInterface
     */
    private $codeGenerator;

    /**
     * @param MovementCodeGeneratorInterface $codeGenerator
     */
    public function __construct(MovementCodeGeneratorInterface $codeGenerator)
    {
        $this->codeGenerator = $codeGenerator;
    }

    /**
     * @param StockItemInterface $stockItem
     * @param UomQty             $qty
     *
     * @return Movement
     */
    public function createDraft(StockItemInterface $stockItem, UomQty $qty): Movement
    {
        $code = $this->codeGenerator->generate($stockItem, $qty);

        return Movement::createDefault($code, $stockItem, $qty);
    }
}
