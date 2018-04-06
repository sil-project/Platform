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
use Sil\Component\Product\Model\OptionTypeInterface;
use Sil\Component\Product\Repository\OptionTypeRepositoryInterface;

class OptionTypeToIdTransformer implements DataTransformerInterface
{
    /**
     * @var OptionTypeRepositoryInterface
     */
    protected $optionTypeRepository;

    public function transform($value): ?string
    {
        if ($value instanceof OptionTypeInterface) {
            return $value->getId();
        } else {
            return null;
        }
    }

    public function reverseTransform($value): OptionTypeInterface
    {
        return $this->optionTypeRepository->get($value);
    }

    /**
     * @param OptionTypeRepositoryInterface $optionTypeRepository
     */
    public function setOptionTypeRepository(OptionTypeRepositoryInterface $optionTypeRepository): void
    {
        $this->optionTypeRepository = $optionTypeRepository;
    }
}
