<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Model;

use Symfony\Component\PropertyAccess\PropertyAccess;

class FieldValue implements UiModelInterface
{
    protected $type;
    protected $template;
    protected $dataAccessor;
    protected $valueTemplate;

    public function getName(): string
    {
        return $this->getType();
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getDataAccessor()
    {
        return $this->dataAccessor;
    }

    public function setDataAccessor(string $dataAccessor)
    {
        $this->dataAccessor = $dataAccessor;
    }

    public function getValueTemplate()
    {
        return   $this->valueTemplate;
    }

    public function setValueTemplate(string $valueTemplate)
    {
        $this->valueTemplate = $valueTemplate;
    }

    public function valueFrom($data)
    {
        if (null === $this->dataAccessor) {
            return $data;
        }

        return PropertyAccess::createPropertyAccessor()->getValue($data, $this->dataAccessor);
    }

    public function renderUsing($renderer, $data)
    {
        return $renderer->renderFieldValue($this, $data);
    }
}
