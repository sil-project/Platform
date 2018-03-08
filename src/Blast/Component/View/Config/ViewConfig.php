<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\View\Config;

use Blast\Component\View\DataMapper\DataMapperInterface;
use Blast\Component\View\DataTransformer\DataTransformerInterface;
use Blast\Component\View\Type\ViewTypeInterface;
/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class ViewConfig implements ViewConfigInterface
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var PropertyPathInterface
     */
    private $propertyPath;
    /**
     * @var bool
     */
    private $compound = false;

    /**
     * @var callable
     */
    private $condition;
    /**
     * @var ViewTypeInterface
     */
    private $type;
    /**
     * @var string
     */
    private $dataClass;

    /**
     * @var array
     */
    private $dataTransformers = array();

    /**
     * @var DataMapperInterface
     */
    private $dataMapper;
    /**
     * @var bool
     */
    private $inheritData = false;
    /**
     * @var bool
     */
    private $dataLocked;
    /**
     * @var mixed
     */
    private $emptyData;
    /**
     * @var array
     */
    private $attributes = array();
    /**
     * @var mixed
     */
    private $data;
    /**
     * @var bool
     */
    private $mapped = true;
    /**
     * @var array
     */
    private $options;

    public function __construct(?string $name, ?string $dataClass, array $options)
    {
        $this->name = $name;
        $this->dataClass = $dataClass;
        $this->options = $options;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDataClass()
    {
        return $this->dataClass;
    }

    public function getEventDispatcher()
    {
        return $this->dispatcher;
    }

    public function getDataTransformers()
    {
        return $this->dataTransformers;
    }

    public function addDataTransformer(DataTransformerInterface $dataTransformer, $forcePrepend = false)
    {
        if ($forcePrepend) {
            array_unshift($this->dataTransformers, $dataTransformer);
        } else {
            $this->dataTransformers[] = $dataTransformer;
        }
    }

    public function getDataMapper()
    {
        return $this->dataMapper;
    }

    public function getPropertyPath()
    {
        return $this->propertyPath;
    }

    public function getInheritData()
    {
        return $this->inheritData;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getEmptyData()
    {
        return $this->emptyData;
    }

    /**
     * {@inheritdoc}
     */
    public function isMapped()
    {
        return $this->mapped;
    }

    public function setMapped(bool $mapped)
    {
        $this->mapped = $mapped;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function hasAttribute($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($name, $default = null)
    {
        return array_key_exists($name, $this->attributes) ? $this->attributes[$name] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    public function getDataLocked()
    {
        return $this->dataLocked;
    }

    public function getCompound()
    {
        return $this->compound;
    }

    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($name, $default = null)
    {
        return array_key_exists($name, $this->options) ? $this->options[$name] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function setDataMapper(DataMapperInterface $dataMapper = null)
    {
        $this->dataMapper = $dataMapper;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setType(ViewTypeInterface $type)
    {
        $this->type = $type;
    }

    public function setInheritData($inheritData)
    {
        $this->inheritData = $inheritData;
    }

    public function setCondition(?callable $condition)
    {
        $this->condition = $condition;
    }

    public function checkCondition($data)
    {
        return ($this->condition)($data);
    }

    public function hasCondition()
    {
        return null !== $this->condition;
    }

    public function setCompound($compound)
    {
        $this->compound = $compound;
    }

    public function setDataLocked($locked)
    {
        $this->dataLocked = $locked;
    }
}
