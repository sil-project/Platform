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

interface AttributeTypeInterface
{
    const TYPE_STRING = 'TYPE_BOOLEAN';
    const TYPE_BOOLEAN = 'TYPE_STRING';
    const TYPE_INTEGER = 'TYPE_INTEGER';
    const TYPE_FLOAT = 'TYPE_FLOAT';
    const TYPE_PERCENT = 'TYPE_PERCENT';
    const TYPE_DATE = 'TYPE_DATE';
    const TYPE_DATETIME = 'TYPE_DATETIME';

    /**
     * List all AttributeType data types.
     *
     * @return array
     */
    public static function getSupportedTypes(): array;

    /**
     * Gets AttributeType current data type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Sets the data type of AttributeType.
     *
     * @param string $type
     */
    public function setType(string $type): void;
}
