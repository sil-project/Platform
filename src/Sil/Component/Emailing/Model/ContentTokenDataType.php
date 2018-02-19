<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Emailing\Model;

use InvalidArgumentException;

class ContentTokenDataType
{
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';
    const TYPE_DATE = 'date';
    const TYPE_DATETIME = 'datetime';

    /**
     * Name of the token type.
     *
     * @var string
     */
    protected $value;

    public function __construct(string $value)
    {
        if (!in_array($value, $this->getTypes())) {
            throw new InvalidArgumentException(sprintf('Type « %s » is not managed. Managed types are : %s', $value, implode(', ', $this->getTypes())));
        }
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function getTypes(): array
    {
        return [
            'TYPE_BOOLEAN'  => static::TYPE_BOOLEAN,
            'TYPE_STRING'   => static::TYPE_STRING,
            'TYPE_INTEGER'  => static::TYPE_INTEGER,
            'TYPE_FLOAT'    => static::TYPE_FLOAT,
            'TYPE_DATE'     => static::TYPE_DATE,
            'TYPE_DATETIME' => static::TYPE_DATETIME,
        ];
    }
}
