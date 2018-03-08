<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\View\DataMapper;

use Blast\Component\View\DataMapperInterface;
use Blast\Component\View\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
  * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
  */
 class PropertyPathMapper implements DataMapperInterface
 {
     private $propertyAccessor;

     public function __construct(PropertyAccessorInterface $propertyAccessor = null)
     {
         $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
     }

     /**
      * {@inheritdoc}
      */
     public function mapDataToViews($data, $views)
     {
         $empty = null === $data || array() === $data;
         if (!$empty && !is_array($data) && !is_object($data)) {
             throw new UnexpectedTypeException($data, 'object, array or empty');
         }
         foreach ($views as $view) {
             $propertyPath = $view->getPropertyPath();
             $config = $view->getConfig();
             if (!$empty && null !== $propertyPath && $config->isMapped()) {
                 $view->setData($this->propertyAccessor->getValue($data, $propertyPath));
             } else {
                 $view->setData($view->getConfig()->getData());
             }
         }
     }

     /**
      * {@inheritdoc}
      */
     public function mapViewsToData($views, &$data)
     {
         if (null === $data) {
             return;
         }
         if (!is_array($data) && !is_object($data)) {
             throw new UnexpectedTypeException($data, 'object, array or empty');
         }
         foreach ($views as $view) {
             $propertyPath = $view->getPropertyPath();
             $config = $view->getConfig();

             if (null !== $propertyPath && $config->getMapped()) {
                 // If the field is of type DateTime and the data is the same skip the update to
                 // keep the original object hash
                 if ($view->getData() instanceof \DateTime && $view->getData() == $this->propertyAccessor->getValue($data, $propertyPath)) {
                     continue;
                 }
                 // If the data is identical to the value in $data, we are
                 // dealing with a reference
                 if (!is_object($data) || !$config->getByReference() || $view->getData() !== $this->propertyAccessor->getValue($data, $propertyPath)) {
                     $this->propertyAccessor->setValue($data, $propertyPath, $view->getData());
                 }
             }
         }
     }
 }
