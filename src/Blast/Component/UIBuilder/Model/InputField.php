<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Model;

class InputField extends Field
{
    /**
     * TextArea specific.
     *
     * @var int
     */
    protected $rows = 2;

    public function getRows()
    {
        return $this->rows;
    }

    public function getFormView($formFactory, $data)
    {
        $builder = $formFactory->createNamedBuilder($this->getName(), $this->getType(), $this->getValue()->valueFrom($data));

        return $builder->getForm()->createView();
    }

    public function renderUsing($renderer, $data)
    {
        return $renderer->renderInputField($this, $data);
    }
}
