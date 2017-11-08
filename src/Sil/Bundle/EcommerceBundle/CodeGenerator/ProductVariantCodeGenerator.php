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

namespace Sil\Bundle\EcommerceBundle\CodeGenerator;

use Doctrine\ORM\EntityManager;
use Blast\Bundle\CoreBundle\CodeGenerator\CodeGeneratorInterface;
use Blast\Bundle\CoreBundle\Exception\InvalidEntityCodeException;
use Sil\Bundle\EcommerceBundle\Entity\ProductVariant;

class ProductVariantCodeGenerator implements CodeGeneratorInterface
{
    const ENTITY_CLASS = 'Sil\Bundle\EcommerceBundle\Entity\ProductVariant';
    const ENTITY_FIELD = 'code';

    private static $length = 3;

    /**
     * @var EntityManager
     */
    protected static $em;

    public static function setEntityManager(EntityManager $em)
    {
        self::$em = $em;
    }

    /**
     * @param ProductVariant $productVariant
     *
     * @return string
     *
     * @throws InvalidEntityCodeException
     */
    public static function generate($productVariant)
    {
        if (!$product = $productVariant->getProduct()) {
            throw new InvalidEntityCodeException('sil.error.missing_product');
        }
        if (!$productCode = $product->getCode()) {
            throw new InvalidEntityCodeException('sil.error.missing_product_code');
        }
        // TODO: improve this (use productVariant name or optionValues...) and handle code unicity
        return sprintf('%s-%s', $productCode, strtoupper($productVariant->getName()));
    }

    /**
     * @param string         $code
     * @param ProductVariant $productVariant
     *
     * @return bool
     *
     * @todo   ...
     */
    public static function validate($code, $productVariant = null)
    {
        return true;
    }

    /**
     * @return string
     *
     * @todo   ...
     */
    public static function getHelp()
    {
        return '';
    }
}
