<?php

declare(strict_types=1);

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Entity;

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;

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
     * @var string
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
     * @param string $name
     * @param Uom    $uom
     */
    public static function creatDefault(string $code, string $name, Uom $uom,
            OutputStrategy $strategy)
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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
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
     * @param string $code
     */
    public function setCode(string $code): void
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
