<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Model;

class Field extends UiModel
{
    protected $label;
    protected $labelIcon;
    protected $type;
    /**
     * @var FieldValue
     */
    protected $value;

    public function __construct(string $name, array $options = [])
    {
        $this->value = new FieldValue();
        parent::__construct($name, $options);
    }

    public function getLabel()
    {
        return   $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getLabelIcon()
    {
        return   $this->labelIcon;
    }

    public function setLabelIcon($labelIcon)
    {
        $this->labelIcon = $labelIcon;
    }

    public function getType()
    {
        return $this->value->getType();
    }

    public function setType($type)
    {
        $this->value->setType($type);
    }

    public function getValue()
    {
        return   $this->value;
    }

    public function setValue(FieldValue $value)
    {
        $this->value = $value;
    }

    public function getValueAccessor()
    {
        return   $this->value->getDataAccessor();
    }

    public function setValueAccessor(string $valueAccessor)
    {
        $this->value->setDataAccessor($valueAccessor);
    }

    public function getValueTemplate()
    {
        return   $this->value->getValueTemplate();
    }

    public function setValueTemplate(string $valueTemplate)
    {
        $this->value->setValueTemplate($valueTemplate);
    }

    public function renderUsing($renderer, $data)
    {
        return $renderer->renderField($this, $data);
    }
}
