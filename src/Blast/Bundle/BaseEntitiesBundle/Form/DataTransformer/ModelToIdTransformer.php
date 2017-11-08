<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Form\DataTransformer;

use Doctrine\Common\Util\ClassUtils;
use Sonata\AdminBundle\Model\ModelManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Transform object to ID and label.
 *
 * @author Romain SANCHEZ <romain.sanchez@libre-informatique.fr>
 */
class ModelToIdTransformer implements DataTransformerInterface
{
    /**
     * @var ModelManagerInterface
     */
    protected $modelManager;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var bool
     */
    protected $multiple;

    /**
     * @var callable|null
     */
    protected $toStringCallback;

    /**
     * @param ModelManagerInterface $modelManager
     * @param string                $className
     * @param string                $property
     * @param bool                  $multiple
     * @param null                  $toStringCallback
     */
    public function __construct(ModelManagerInterface $modelManager, $className, $multiple = false, $toStringCallback = null)
    {
        $this->modelManager = $modelManager;
        $this->className = $className;
        $this->multiple = $multiple;
        $this->toStringCallback = $toStringCallback;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        $collection = $this->modelManager->getModelCollectionInstance($this->className);

        if (empty($value)) {
            if ($this->multiple) {
                return $collection;
            }

            return;
        }

        if (!$this->multiple) {
            return $this->modelManager->find($this->className, $value);
        }

        if (!is_array($value)) {
            throw new \UnexpectedValueException(sprintf('Value should be array, %s given.', gettype($value)));
        }
        foreach ($value as $key => $id) {
            if ($key === '_labels') {
                continue;
            }

            $collection->add($this->modelManager->find($this->className, $id));
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($entityOrCollection)
    {
        $result = array();

        if (!$entityOrCollection) {
            return $result;
        }

        if ($this->multiple) {
            $isArray = is_array($entityOrCollection);
            if (!$isArray && substr(get_class($entityOrCollection), -1 * strlen($this->className)) == $this->className) {
                throw new \InvalidArgumentException('A multiple selection must be passed a collection not a single value. Make sure that form option "multiple=false" is set for many-to-one relation and "multiple=true" is set for many-to-many or one-to-many relations.');
            } elseif ($isArray || ($entityOrCollection instanceof \ArrayAccess)) {
                $collection = $entityOrCollection;
            } else {
                throw new \InvalidArgumentException('A multiple selection must be passed a collection not a single value. Make sure that form option "multiple=false" is set for many-to-one relation and "multiple=true" is set for many-to-many or one-to-many relations.');
            }
        } else {
            if (substr(get_class($entityOrCollection), -1 * strlen($this->className)) == $this->className) {
                $collection = array($entityOrCollection);
            } elseif ($entityOrCollection instanceof \ArrayAccess) {
                throw new \InvalidArgumentException('A single selection must be passed a single value not a collection. Make sure that form option "multiple=false" is set for many-to-one relation and "multiple=true" is set for many-to-many or one-to-many relations.');
            } else {
                $collection = array($entityOrCollection);
            }
        }

        foreach ($collection as $entity) {
            if (!$entity) {
                continue;
            }

            $id = current($this->modelManager->getIdentifierValues($entity));

            if ($this->toStringCallback !== null) {
                if (!is_callable($this->toStringCallback)) {
                    throw new \RuntimeException('Callback in "to_string_callback" option doesn`t contain a callable function.');
                }
                $label = call_user_func($this->toStringCallback, $entity);
            } else {
                try {
                    $label = (string) $entity;
                } catch (\Exception $e) {
                    throw new \RuntimeException(sprintf("Unable to convert the entity %s to String, entity must have a '__toString()' method defined", ClassUtils::getClass($entity)), 0, $e);
                }
            }

            $result[] = $id;
            $result['_labels'][] = $label;
        }

        return $result;
    }
}
