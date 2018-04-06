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
use Sil\Component\Product\Model\AttributeTypeInterface;
use Sil\Component\Product\Repository\AttributeTypeRepositoryInterface;
use Sil\Bundle\ProductBundle\Form\Transformer\Exception\TransformationFailedException;

class ArrayToAttributeTypeTransformer implements DataTransformerInterface
{
    /**
     * @var string
     */
    protected $attributeTypeClass;

    /**
     * @var array
     */
    protected $mandatoryFields = ['type', 'name'];

    /**
     * @var AttributeTypeRepositoryInterface
     */
    protected $attributeTypeRepository;

    /**
     * {@inheritdoc}
     */
    public function transform($value): array
    {
        if (!$value instanceof AttributeTypeInterface) {
            $attributeType = [
               'id'       => null,
               'name'     => null,
               'type'     => null,
               'reusable' => null,
           ];
        } else {
            $attributeType = [
               'id'       => $value->getId(),
               'name'     => $value->getName(),
               'type'     => $value->getType(),
               'reusable' => $value->isReusable(),
            ];
        }

        return $attributeType;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value): AttributeTypeInterface
    {
        if (isset($value['id'])) {
            $attributeType = $this->attributeTypeRepository->get($value['id']);
        } else {
            if (count(array_intersect(array_keys($value), $this->mandatoryFields)) != count($this->mandatoryFields)) {
                throw new TransformationFailedException(
                    AttributeTypeInterface::class,
                    $value,
                    $this->mandatoryFields
                );
            }

            $attributeType = new $this->attributeTypeClass($value['name'], $value['type']);
        }

        // Setting data from submitted ones
        $attributeType->setReusable($value['reusable']);

        return $attributeType;
    }

    /**
     * @param string $attributeTypeClass
     */
    public function setAttributeTypeClass(string $attributeTypeClass): void
    {
        $this->attributeTypeClass = $attributeTypeClass;
    }

    /**
     * @param AttributeTypeRepositoryInterface $attributeTypeRepository
     */
    public function setAttributeTypeRepository(AttributeTypeRepositoryInterface $attributeTypeRepository): void
    {
        $this->attributeTypeRepository = $attributeTypeRepository;
    }
}
