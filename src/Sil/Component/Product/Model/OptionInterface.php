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

interface OptionInterface
{
    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @param string $value
     */
    public function setValue(string $value): void;

    /**
     * @return OptionTypeInterface
     */
    public function getOptionType(): OptionTypeInterface;

    /**
     * @param OptionTypeInterface $optionType
     */
    public function setOptionType(OptionTypeInterface $optionType): void;
}
