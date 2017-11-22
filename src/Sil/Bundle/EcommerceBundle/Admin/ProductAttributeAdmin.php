<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Admin;

use Sylius\Component\Attribute\AttributeType\CheckboxAttributeType;
use Sylius\Component\Attribute\AttributeType\DateAttributeType;
use Sylius\Component\Attribute\AttributeType\DatetimeAttributeType;
use Sylius\Component\Attribute\AttributeType\IntegerAttributeType;
use Sylius\Component\Attribute\AttributeType\PercentAttributeType;
use Sylius\Component\Attribute\Factory\AttributeFactoryInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Product\Model\ProductAttributeInterface;

class ProductAttributeAdmin extends SyliusGenericAdmin
{
    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.ecommerce.product_attribute';

    /**
     * @return ProductAttributeInterface
     */
    public function getNewInstance()
    {
        /**
         * @var AttributeFactoryInterface
         */
        $attributeFactory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.product_attribute');

        /**
         * @var ProductAttributeInterface
         */
        /* @todo: find a way to use parent getNewInstance from SyliusGenericAdmin */
        $object = $attributeFactory->createTyped('text');

        foreach ($this->getExtensions() as $extension) {
            $extension->alterNewInstance($this, $object);
        }

        return $object;
    }

    /**
     * @param ProductAttributeInterface $object
     * @param string                    $method
     */
    public function prePersistOrUpdate($object, $method)
    {
        parent::prePersistOrUpdate($object, $method);

        switch ($object->getType()) {
            case IntegerAttributeType::TYPE:
                $object->setStorageType(AttributeValueInterface::STORAGE_INTEGER);
                break;
            case PercentAttributeType::TYPE:
                $object->setStorageType(AttributeValueInterface::STORAGE_FLOAT);
                break;
            case CheckboxAttributeType::TYPE:
                $object->setStorageType(AttributeValueInterface::STORAGE_BOOLEAN);
                break;
            case DateAttributeType::TYPE:
                $object->setStorageType(AttributeValueInterface::STORAGE_DATE);
                break;
            case DatetimeAttributeType::TYPE:
                $object->setStorageType(AttributeValueInterface::STORAGE_DATETIME);
                break;
            default:
                $object->setStorageType(AttributeValueInterface::STORAGE_TEXT);
        }
    }
}
