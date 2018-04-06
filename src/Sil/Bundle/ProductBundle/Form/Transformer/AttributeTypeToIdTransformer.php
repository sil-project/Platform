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

class AttributeTypeToIdTransformer implements DataTransformerInterface
{
    /**
     * @var AttributeTypeRepositoryInterface
     */
    protected $attributeTypeRepository;

    public function transform($value): ?string
    {
        if ($value instanceof AttributeTypeInterface) {
            return $value->getId();
        }

        return null;
    }

    public function reverseTransform($value): AttributeTypeInterface
    {
        return $this->attributeTypeRepository->get($value);
    }

    /**
     * @param AttributeTypeRepositoryInterface $attributeTypeRepository
     */
    public function setAttributeTypeRepository(AttributeTypeRepositoryInterface $attributeTypeRepository): void
    {
        $this->attributeTypeRepository = $attributeTypeRepository;
    }
}
