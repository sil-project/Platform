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

abstract class AbstractCode implements CodeInterface
{
    /**
     * Product unique identifyer code.
     *
     * @var string
     */
    protected $value;

    /**
     * The code format used for validation.
     *
     * @var string
     */
    protected $format;

    public function __construct($value, $format = '/^.*$/')
    {
        $this->format = $format;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }
}
