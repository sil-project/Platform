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

namespace Sil\Bundle\VarietyBundle\Entity;

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;

/**
 * GenericDescription.
 */
class GenericDescription
{
    use BaseEntity;

    /**
     * @var string
     */
    private $fieldset;

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $value;

    /**
     * Set fieldset.
     *
     * @param string $fieldset
     *
     * @return GenericDescription
     */
    public function setFieldset($fieldset)
    {
        $this->fieldset = $fieldset;

        return $this;
    }

    /**
     * Get fieldset.
     *
     * @return string
     */
    public function getFieldset()
    {
        return $this->fieldset;
    }

    /**
     * Set field.
     *
     * @param string $field
     *
     * @return GenericDescription
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field.
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set value.
     *
     * @param string $value
     *
     * @return GenericDescription
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
