<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Builder;

use  Blast\Component\UIBuilder\Factory\AbstractFactoryInterface;

class UIBuilder extends BaseBuilder
{
    use TabContainerAwareTrait,
        GroupAwareTrait,
        FormAwareTrait,
        FieldAwareTrait;

    public function __construct(AbstractFactoryInterface $abstractFactory, string $name)
    {
        parent::__construct(null, $abstractFactory, $name);
    }

    protected function build()
    {
        return $this->getAbstractFactory()->createView($this->name, $this->options);
    }
}
