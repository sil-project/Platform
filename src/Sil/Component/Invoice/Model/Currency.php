<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Invoice\Model;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class Currency implements CurrencyInterface
{
    /**
     * name.
     *
     * @var string
     */
    protected $name;

    /**
     * code.
     *
     * @var string
     */
    protected $code;

    /**
     * symbol.
     *
     * @var string
     */
    protected $symbol;

    /**
     * @param string $name
     * @param string $code
     * @param string $symbol
     */
    public function __construct(string $name, string $code, string $symbol)
    {
        $this->name = $name;
        $this->code = $code;
        $this->symbol = $symbol;
    }

    /**
     * Get the value of name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name.
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the value of code.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Set the value of code.
     *
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * Get the value of symbol.
     *
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * Set the value of symbol.
     *
     * @param string $symbol
     */
    public function setSymbol(string $symbol): void
    {
        $this->symbol = $symbol;
    }
}
