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
    const TYPE_STRING = 0;
    const TYPE_BOOLEAN = 1;
    const TYPE_INTEGER = 2;
    const TYPE_FLOAT = 3;
    const TYPE_PERCENT = 4;
    const TYPE_DATE = 5;
    const TYPE_DATETIME = 6;

    /**
     * List all AttributeType data types.
     * Array format must be as
     * [
     *     'TYPE_STRING' => AttributeTypeInterface::TYPE_STRING,
     * ].
     *
     * @return array
     */
    public function getSupportedTypes(): array;

    /**
     * Gets AttributeType current data type.
     *
     * @return int
     */
    public function getType(): int;

    /**
     * Sets the data type of AttributeType.
     *
     * @param int $type
     */
    public function setType(int $type): void;
}
