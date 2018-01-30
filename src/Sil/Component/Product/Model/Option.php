<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Product\Model;

class Option
{
    /**
     * Value for attribute.
     *
     * @var string
     */
    protected $value;

    /**
     * The attribute for current value.
     *
     * @var OptionType
     */
    protected $optionType;

    public function __construct(OptionType $optionType, $value)
    {
        $this->optionType = $optionType;
        $optionType->addOption($this); // Update bi-directionnal relationship
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return (string) $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return OptionType
     */
    public function getOptionType(): OptionType
    {
        return $this->optionType;
    }

    /**
     * @param OptionType $optionType
     */
    public function setOptionType(OptionType $optionType): void
    {
        $this->optionType = $optionType;
    }
}
