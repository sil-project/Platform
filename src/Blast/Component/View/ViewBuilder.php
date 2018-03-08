<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\View;

use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\PropertyAccess\PropertyPathInterface;
use Exception\UnexpectedTypeException;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class ViewBuilder implements \IteratorAggregate, ViewBuilderInterface
{
    /**
     * @var ViewConfigInterface
     */
    private $config;

    /**
     * @var FormFactoryInterface
     */
    private $viewFactory;

    protected $unresolvedChildren = [];
    protected $children = [];

    public function __construct(?string $name, ?string $dataClass, ViewFactoryInterface $factory, array $options = array())
    {
        self::validateName($name);
        if (null !== $dataClass && !class_exists($dataClass) && !interface_exists($dataClass)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" not found. Is the "data_class" view option set correctly?', $dataClass));
        }
        $this->config = new ViewConfig($name, $dataClass, $options);
        $this->setViewFactory($factory);
    }

    public function add($child, $type = null, array $options = [])
    {
        $this->children[$child] = null;
        $this->unresolvedChildren[$child] = array(
          'type'    => $type,
          'options' => $options,
        );

        return $this;
    }

    public function create($name, $type = null, array $options = array())
    {
        if (null === $type && null === $this->getDataClass()) {
            $type = 'TextType';
        }
        if (null !== $type) {
            return $this->getViewFactory()->createNamedBuilder($name, $type, null, $options);
        }

        return $this->getViewFactory()->createBuilderForProperty($this->getDataClass(), $name, null, $options);
    }

    public function get($name)
    {
        if (isset($this->unresolvedChildren[$name])) {
            return $this->resolveChild($name);
        }
        if (isset($this->children[$name])) {
            return $this->children[$name];
        }
        throw new InvalidArgumentException(sprintf('The child with the name "%s" does not exist.', $name));
    }

    public function remove($name)
    {
        unset($this->unresolvedChildren[$name], $this->children[$name]);

        return $this;
    }

    public function has($name)
    {
        if (isset($this->unresolvedChildren[$name])) {
            return true;
        }
        if (isset($this->children[$name])) {
            return true;
        }

        return false;
    }

    public function all()
    {
        $this->resolveChildren();

        return $this->children;
    }

    public function count()
    {
        return count($this->children);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->all());
    }

    public function getView()
    {
        $this->resolveChildren();

        $view = new View($this->getViewConfig());

        foreach ($this->children as $child) {
            $view->add($child->getView());
        }

        return $view;
    }

    private function resolveChildren()
    {
        foreach ($this->unresolvedChildren as $name => $info) {
            $this->children[$name] = $this->create($name, $info['type'], $info['options']);
        }
        $this->unresolvedChildren = array();
    }

    private function resolveChild(string $name): self
    {
        $info = $this->unresolvedChildren[$name];
        $child = $this->create($name, $info['type'], $info['options']);
        $this->children[$name] = $child;
        unset($this->unresolvedChildren[$name]);

        return $child;
    }

    public function setViewFactory(ViewFactoryInterface $viewFactory)
    {
        $this->viewFactory = $viewFactory;

        return $this;
    }

    public function getViewFactory(): ViewFactoryInterface
    {
        return $this->viewFactory;
    }

    public function setType(ViewTypeInterface $type)
    {
        $this->config->setType($type);

        return $this;
    }

    public function setMapped(bool $mapped)
    {
        $this->config->setMapped($mapped);

        return $this;
    }

    public function setPropertyPath($propertyPath)
    {
        if (null !== $propertyPath && !$propertyPath instanceof PropertyPathInterface) {
            $propertyPath = new PropertyPath($propertyPath);
        }
        $this->propertyPath = $propertyPath;

        return $this;
    }

    public function setDataMapper(DataMapperInterface $dataMapper = null)
    {
        $this->config->setDataMapper($dataMapper);

        return $this;
    }

    public function setData($data)
    {
        $this->config->setData($data);

        return $this;
    }

    public function setInheritData($inheritData)
    {
        $this->config->setInheritData($inheritData);

        return $this;
    }

    public function setCompound($compound)
    {
        $this->config->setCompound($compound);

        return $this;
    }

    public function setDataLocked($locked)
    {
        $this->config->setDataLocked($locked);

        return $this;
    }

    public function setCondition(?callable $condition)
    {
        $this->config->setCondition($condition);

        return $this;
    }

    public function addDataTransformer(DataTransformerInterface $dataTransformer, $forcePrepend = false)
    {
        $this->config->addDataTransformer($dataTransformer, $forcePrepend);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->config->getOptions();
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption($name)
    {
        return $this->config->hasOption($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($name, $default = null)
    {
        return $this->config->getOption($name, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute($name, $value)
    {
        $this->config->setAttribute($name, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttributes(array $attributes)
    {
        $this->config->setAttributes($attributes);

        return $this;
    }

    public function getViewConfig()
    {
        return $this->config;
    }

    public static function validateName($name)
    {
        if (null !== $name && !is_string($name) && !is_int($name)) {
            throw new UnexpectedTypeException($name, 'string, integer or null');
        }
        if (!self::isValidName($name)) {
            throw new \InvalidArgumentException(sprintf(
                'The name "%s" contains illegal characters. Names should start with a letter, digit or underscore and only contain letters, digits, numbers, underscores ("_"), hyphens ("-") and colons (":").',
                $name
            ));
        }
    }

    public static function isValidName($name)
    {
        return '' === $name || null === $name || preg_match('/^[a-zA-Z0-9_][a-zA-Z0-9_\-:]*$/D', $name);
    }
}
