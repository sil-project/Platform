<?php
/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */
namespace Sil\Bundle\StockBundle\Form\DataTransformer;

use Sil\Bundle\StockBundle\Domain\Entity\OperationType;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class OperationTypeDataTransformer implements DataTransformerInterface
{

    /**
     * 
     * @param OperationType|null $type
     * @return string
     */
    public function transform($type)
    {
        if ( null === $type ) {
            return '';
        }

        return $type->getValue();
    }

    /**
     * 
     * @param string|null $typeValue
     * @return OperationType
     */
    public function reverseTransform($typeValue)
    {
        if ( !$typeValue ) {
            return null;
        }

        return OperationType::$typeValue();
    }
}
