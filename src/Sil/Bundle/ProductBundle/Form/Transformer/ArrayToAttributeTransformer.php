<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ProductBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Sil\Component\Product\Model\AttributeInterface;
use Sil\Component\Product\Repository\AttributeRepositoryInterface;
use Sil\Bundle\ProductBundle\Form\Transformer\Exception\TransformationFailedException;

class ArrayToAttributeTransformer implements DataTransformerInterface
{
    /**
     * @var string
     */
    protected $attributeClass;

    /**
     * @var AttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * @var array
     */
    protected $mandatoryFields = ['attributeType', 'value'];

    /**
     * {@inheritdoc}
     */
    public function transform($value): array
    {
        if ($value instanceof AttributeInterface) {
            return [
               'id'            => $value->getId(),
               'name'          => $value->getName(),
               'specificName'  => $value->getSpecificName(),
               'attributeType' => $value->getAttributeType(),
               'value'         => $value->getValue(),
            ];
        } else {
            return [
                'id'            => null,
                'name'          => null,
                'specificName'  => null,
                'attributeType' => null,
                'value'         => null,
            ];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value): AttributeInterface
    {
        if (isset($value['id'])) {
            $attribute = $this->attributeRepository->get($value['id']);
        } else {
            if (count(array_intersect(array_keys($value), $this->mandatoryFields)) != count($this->mandatoryFields)) {
                throw new TransformationFailedException(
                    AttributeInterface::class,
                    $value,
                    $this->mandatoryFields
                );
            }

            $attribute = new $this->attributeClass($value['attributeType'], $value['value']);
        }

        // Setting data from submitted ones
        if ($value['name'] !== null) {
            $attribute->setName($value['name']);
        }
        $attribute->setSpecificName($value['specificName']);

        return $attribute;
    }

    /**
     * @param string $attributeClass
     */
    public function setAttributeClass(string $attributeClass): void
    {
        $this->attributeClass = $attributeClass;
    }

    /**
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function setAttributeRepository(AttributeRepositoryInterface $attributeRepository): void
    {
        $this->attributeRepository = $attributeRepository;
    }
}
