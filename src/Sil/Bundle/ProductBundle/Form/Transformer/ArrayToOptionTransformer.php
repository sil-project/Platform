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
use Sil\Bundle\ProductBundle\Form\Transformer\Exception\TransformationFailedException;
use Sil\Component\Product\Model\OptionInterface;
use Sil\Component\Product\Repository\OptionRepositoryInterface;

class ArrayToOptionTransformer implements DataTransformerInterface
{
    /**
     * @var string
     */
    protected $optionClass;

    /**
     * @var OptionRepositoryInterface
     */
    protected $optionRepository;

    /**
     * @var array
     */
    protected $mandatoryFields = ['optionType', 'value'];

    /**
     * {@inheritdoc}
     */
    public function transform($value): array
    {
        $baseArray = [
            'id'         => null,
            'optionType' => null,
            'value'      => null,
        ];

        if ($value instanceof OptionInterface) {
            return [
               'id'         => $value->getId(),
               'optionType' => $value->getAttributeType(),
               'value'      => $value->getValue(),
            ];
        }

        if (is_array($value)) {
            return array_merge($baseArray, $value);
        }

        return $baseArray;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value): OptionInterface
    {
        if (isset($value['id'])) {
            $option = $this->optionRepository->get($value['id']);
        } else {
            if (count(array_intersect(array_keys($value), $this->mandatoryFields)) != count($this->mandatoryFields)) {
                throw new TransformationFailedException(
                    OptionInterface::class,
                    $value,
                    $this->mandatoryFields
                );
            }

            $option = new $this->optionClass($value['optionType'], $value['value']);
        }

        return $option;
    }

    /**
     * @param string $optionClass
     */
    public function setOptionClass(string $optionClass): void
    {
        $this->optionClass = $optionClass;
    }

    /**
     * @param OptionRepositoryInterface $optionRepository
     */
    public function setOptionRepository(OptionRepositoryInterface $optionRepository): void
    {
        $this->optionRepository = $optionRepository;
    }
}
