<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Contact\Model;

use Blast\Component\Resource\Model\ResourceInterface;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class Country implements ResourceInterface
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
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
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
}
