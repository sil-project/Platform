<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Factory;

use Blast\Component\UIBuilder\Model;

class BaseAbstractFactory implements AbstractFactoryInterface
{
    public function createTab(string $name, array $options)
    {
        return new Model\Tab($name, $options);
    }

    public function createGroup(string $name, array $options)
    {
        return new Model\Group($name, $options);
    }

    public function createFieldGroup(string $name, array $options)
    {
        return new Model\FieldGroup($name, $options);
    }

    public function createField(string $name, array $options)
    {
        return new Model\Field($name, $options);
    }

    public function createInputField(string $name, array $options)
    {
        return new Model\InputField($name, $options);
    }

    public function createForm(string $name, array $options)
    {
        return new Model\Form($name, $options);
    }

    public function createTabContainer(string $name, array $options)
    {
        return new Model\TabContainer($name, $options);
    }

    public function createCollection(string $name, array $options)
    {
        return new Model\Collection($name, $options);
    }

    public function createView(string $name, array $options)
    {
        return new Model\View($name, $options);
    }
}
