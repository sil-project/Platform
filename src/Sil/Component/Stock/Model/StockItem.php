<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Stock\Model;

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;
use Sil\Component\Uom\Model\Uom;
use Blast\Component\Code\Model\CodeInterface;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class StockItem implements StockItemInterface
{
    use Guidable;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var CodeInterface
     */
    protected $code;

    /**
     * @var Uom
     */
    protected $uom;

    /**
     * @var OutputStrategy
     */
    protected $outputStrategy;

    /**
     * @param CodeInterface  $code
     * @param string         $name
     * @param Uom            $uom
     * @param OutputStrategy $strategy
     */
    public static function creatDefault(CodeInterface $code, string $name, Uom $uom, OutputStrategy $strategy)
    {
        $o = new self();
        $o->code = $code;
        $o->name = $name;
        $o->uom = $uom;
        $o->outputStrategy = $strategy;

        return $o;
    }

    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return CodeInterface
     */
    public function getCode(): CodeInterface
    {
        return $this->code;
    }

    /**
     * @return Uom
     */
    public function getUom(): ?Uom
    {
        return $this->uom;
    }

    /**
     * @return OutputStrategy
     */
    public function getOutputStrategy(): ?OutputStrategy
    {
        return $this->outputStrategy;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param CodeInterface $code
     */
    public function setCode(CodeInterface $code): void
    {
        $this->code = $code;
    }

    /**
     * @param Uom $uom
     */
    public function setUom(Uom $uom): void
    {
        $this->uom = $uom;
    }

    /**
     * @param OutputStrategy $outputStrategy
     */
    public function setOutputStrategy(OutputStrategy $outputStrategy): void
    {
        $this->outputStrategy = $outputStrategy;
    }
}
