<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Entity\Traits;

trait Labelable
{
    /**
     * @var string
     */
    private $label;

    /**
     * @param string $label
     *
     * @return Addressable
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    public function __toString()
    {
        // @TODO: should use Stringable from traits
        return (string) ($this->label ? $this->label : (method_exists($this, 'getId') ? $this->getId() : ''));
    }
}
