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

use Blast\Component\View\Exception\LogicException;
use Blast\Component\View\Config\ViewConfigInterface;
use RuntimeException;
use Symfony\Component\Form\Util\OrderedHashMap;
use Symfony\Component\Form\Util\InheritDataAwareIterator;
use Symfony\Component\PropertyAccess\PropertyPath;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class View implements ViewInterface, \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * The parent of this view.
     *
     * @var ViewInterface
     */
    public $parent;
    /**
     * The view's configuration.
     *
     * @var ViewConfigInterface
     */
    private $config;

    /**
     * The children of this view.
     *
     * @var ViewInterface[] A map of ViewInterface instances
     */
    public $children;
    /**
     * The view data.
     *
     * @var mixed
     */
    private $viewData;

    /**
     * @var bool
     */
    private $defaultDataSet = false;

    /**
     * @var bool
     */
    private $lockSetData = false;

    public function __construct(ViewConfigInterface $config)
    {
        if ($config->getCompound() && !$config->getDataMapper()) {
            throw new LogicException('Compound views need a data mapper');
        }

        if ($config->getInheritData()) {
            $this->defaultDataSet = true;
        }

        $this->config = $config;
        $this->children = new OrderedHashMap();
    }

    public function __clone()
    {
        $this->children = clone $this->children;

        foreach ($this->children as $key => $child) {
            $this->children[$key] = clone $child;
        }
    }

    public function createRenderingView(RenderingView $parent = null)
    {
        if ($this->config->hasCondition() && !$this->config->checkCondition($this)) {
            return null;
        }
        $type = $this->config->getType();
        $options = $this->config->getOptions();
        $rview = $type->createRenderingView($this, $parent);
        $type->buildRenderingView($rview, $this, $options);

        foreach ($this->children as $name => $child) {
            $childrview = $child->createRenderingView($rview);

            if (null !== $childrview) {
                $rview->children[$name] = $childrview;
            }
        }

        $type->finishRenderingView($rview, $this, $options);

        return $rview;
    }

    public function getName()
    {
        return $this->config->getName();
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getPropertyPath()
    {
        if (null !== $this->config->getPropertyPath()) {
            return $this->config->getPropertyPath();
        }

        if (null === $this->getName() || '' === $this->getName()) {
            return;
        }

        $parent = $this->parent;

        while ($parent && $parent->getConfig()->getInheritData()) {
            $parent = $parent->getParent();
        }

        /*if ($parent && null === $parent->getConfig()->getDataClass()) {
            return new PropertyPath('[' . $this->getName() . ']');
        }*/

        return new PropertyPath($this->getName());
    }

    public function getViewData()
    {
        if ($this->config->getInheritData()) {
            if (!$this->parent) {
                throw new \RuntimeException('The view is configured to inherit its parent\'s data, but does not have a parent.');
            }

            return $this->parent->getViewData();
        }
        if (!$this->defaultDataSet) {
            $this->setData($this->config->getData());
        }

        return $this->viewData;
    }

    public function getData()
    {
        if ($this->config->getInheritData()) {
            if (!$this->parent) {
                throw new RuntimeException('The form is configured to inherit its parent\'s data, but does not have a parent.');
            }

            return $this->parent->getData();
        }

        if (!$this->defaultDataSet) {
            if ($this->lockSetData) {
                throw new \RuntimeException('A cycle was detected.');
            }

            $this->setData($this->config->getData());
        }

        return $this->modelData;
    }

    public function setData($modelData)
    {
        // If the form inherits its parent's data, disallow data setting to
        // prevent merge conflicts
        if ($this->config->getInheritData()) {
            throw new RuntimeException('You cannot change the data of a view inheriting its parent data.');
        }
        // Don't allow modifications of the configured data if the data is locked
        if ($this->config->getDataLocked() && $modelData !== $this->config->getData()) {
            return $this;
        }
        /*if (is_object($modelData) && !$this->config->getByReference()) {
            $modelData = clone $modelData;
        }*/
        if ($this->lockSetData) {
            throw new RuntimeException('A cycle was detected. Listeners to the PRE_SET_DATA event must not call setData(). You should call setData() on the FormEvent object instead.');
        }

        $this->lockSetData = true;
        // Hook to change content of the data
        $this->config->getType()->onPreSetData($this, $modelData);

        // Treat data as strings unless a value transformer exists
        if (!$this->config->getDataTransformers() && is_scalar($modelData)) {
            $modelData = (string) $modelData;
        }

        $viewData = $this->modelToViewData($modelData);
        // Validate if view data matches data class (unless empty)
        if (null === $viewData || '' === $viewData) {
            $dataClass = $this->config->getDataClass();

            if (null !== $dataClass && !$viewData instanceof $dataClass) {
                $actualType = is_object($viewData)
                ? 'an instance of class ' . get_class($viewData)
                : 'a(n) ' . gettype($viewData);
                throw new \LogicException(
                    'The view\'s data is expected to be an instance of class ' .
                    $dataClass . ', but is ' . $actualType . '. You can avoid this error ' .
                    'by setting the "data_class" option to null or by adding a data ' .
                    'transformer that transforms ' . $actualType . ' to an instance of ' .
                    $dataClass . '.'
                );
            }
        }
        $this->modelData = $modelData;
        $this->viewData = $viewData;
        $this->defaultDataSet = true;
        $this->lockSetData = false;
        // It is not necessary to invoke this method if the form doesn't have children,
        // even if the form is compound.
        if (count($this->children) > 0) {
            // Update child forms from the data
            $iterator = new InheritDataAwareIterator($this->children);
            $iterator = new \RecursiveIteratorIterator($iterator);
            $this->config->getDataMapper()->mapDataToViews($viewData, $iterator);
        }

        $this->config->getType()->onPostSetData($this, $modelData);

        return $this;
    }

    /**
     * Normalizes the value if a normalization transformer is set.
     *
     * @param mixed $value The value to transform
     *
     * @return mixed
     *
     * @throws TransformationFailedException If the value cannot be transformed to "normalized" format
     */
    private function modelToViewData($value)
    {
        try {
            foreach ($this->config->getDataTransformers() as $transformer) {
                $value = $transformer->transform($value);
            }
        } catch (TransformationFailedException $exception) {
            throw new TransformationFailedException(
            'Unable to transform value for property path "' . $this->getPropertyPath() . '": ' . $exception->getMessage(),
            $exception->getCode(),
            $exception
        );
        }

        return $value;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent(ViewInterface $parent = null)
    {
        if (null !== $parent && '' === $this->config->getName()) {
            throw new LogicException('A view with an empty name cannot have a parent view.');
        }
        $this->parent = $parent;

        return $this;
    }

    public function hasParent()
    {
        return null !== $this->parent;
    }

    public function getRoot()
    {
        return $this->isRoot() ? $this : $this->parent->getRoot();
    }

    public function isRoot()
    {
        return null === $this->parent;
    }

    public function add(ViewInterface $child, $type = null, array $options = array())
    {
        if (!$this->config->getCompound()) {
            throw new \LogicException('You cannot add children to a simple view. Maybe you should set the option "compound" to true?');
        }

        $viewData = null;

        if (!$this->lockSetData && $this->defaultDataSet && !$this->config->getInheritData()) {
            $viewData = $this->getViewData();
        }

        $this->children[$child->getName()] = $child;
        $child->setParent($this);

        if (!$this->lockSetData && $this->defaultDataSet && !$this->config->getInheritData()) {
            $iterator = new InheritDataAwareIterator(new \ArrayIterator(array($child->getName() => $child)));
            $iterator = new \RecursiveIteratorIterator($iterator);
            $this->config->getDataMapper()->mapDataToForms($viewData, $iterator);
        }

        return $this;
    }

    public function all()
    {
        return iterator_to_array($this->children);
    }

    public function has($name)
    {
        return isset($this->children[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        if (isset($this->children[$name])) {
            return $this->children[$name];
        }
        throw new OutOfBoundsException(sprintf('Child "%s" does not exist.', $name));
    }

    /**
     * Returns whether a child with the given name exists (implements the \ArrayAccess interface).
     *
     * @param string $name The name of the child
     *
     * @return bool
     */
    public function offsetExists($name)
    {
        return $this->has($name);
    }

    /**
     * Returns the child with the given name (implements the \ArrayAccess interface).
     *
     * @param string $name The name of the child
     *
     * @return FormInterface The child form
     *
     * @throws \OutOfBoundsException if the named child does not exist
     */
    public function offsetGet($name)
    {
        return $this->get($name);
    }

    /**
     * Adds a child to the form (implements the \ArrayAccess interface).
     *
     * @param string        $name  Ignored. The name of the child is used
     * @param FormInterface $child The child to be added
     *
     * @throws AlreadySubmittedException if the form has already been submitted
     * @throws LogicException            when trying to add a child to a non-compound form
     *
     * @see self::add()
     */
    public function offsetSet($name, $child)
    {
        $this->add($child);
    }

    /**
     * Removes the child with the given name from the form (implements the \ArrayAccess interface).
     *
     * @param string $name The name of the child to remove
     *
     * @throws AlreadySubmittedException if the form has already been submitted
     */
    public function offsetUnset($name)
    {
        $this->remove($name);
    }

    /**
     * Returns the iterator for this group.
     *
     * @return \Traversable|FormInterface[]
     */
    public function getIterator()
    {
        return $this->children;
    }

    /**
     * Returns the number of form children (implements the \Countable interface).
     *
     * @return int The number of embedded form children
     */
    public function count()
    {
        return count($this->children);
    }
}
