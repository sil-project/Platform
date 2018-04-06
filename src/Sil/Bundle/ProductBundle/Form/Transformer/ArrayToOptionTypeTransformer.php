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

use Sil\Bundle\ProductBundle\Form\Transformer\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;
use Sil\Component\Product\Model\OptionTypeInterface;
use Sil\Component\Product\Repository\OptionTypeRepositoryInterface;

class ArrayToOptionTypeTransformer implements DataTransformerInterface
{
    /**
     * @var string
     */
    protected $optionTypeClass;

    /**
     * @var array
     */
    protected $mandatoryFields = ['name'];

    /**
     * @var OptionTypeRepositoryInterface
     */
    protected $optionTypeRepository;

    /**
     * {@inheritdoc}
     */
    public function transform($value): array
    {
        $optionType = [
           'id'   => null,
           'name' => null,
        ];

        if ($value instanceof OptionTypeInterface) {
            $optionType = [
               'id'       => $value->getId(),
               'name'     => $value->getName(),
            ];
        }

        return $optionType;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value): OptionTypeInterface
    {
        if (isset($value['id'])) {
            $optionType = $this->optionTypeRepository->get($value['id']);
        } else {
            if (count(array_intersect(array_keys($value), $this->mandatoryFields)) != count($this->mandatoryFields)) {
                throw new TransformationFailedException(
                    OptionTypeInterface::class,
                    $value,
                    $this->mandatoryFields
                );
            }

            $optionType = new $this->optionTypeClass($value['name']);
        }

        return $optionType;
    }

    /**
     * @param string $optionTypeClass
     */
    public function setOptionTypeClass(string $optionTypeClass): void
    {
        $this->optionTypeClass = $optionTypeClass;
    }

    /**
     * @param OptionTypeRepositoryInterface $optionTypeRepository
     */
    public function setOptionTypeRepository(OptionTypeRepositoryInterface $optionTypeRepository): void
    {
        $this->optionTypeRepository = $optionTypeRepository;
    }
}
