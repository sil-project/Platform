<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\CodeGenerator;

use Doctrine\ORM\EntityManager;
use Blast\Bundle\CoreBundle\CodeGenerator\CodeGeneratorInterface;
use Blast\Bundle\CoreBundle\Exception\InvalidEntityCodeException;
use Sil\Bundle\EcommerceBundle\Entity\Product;

class ProductCodeGenerator implements CodeGeneratorInterface
{
    const ENTITY_CLASS = 'Sil\Bundle\EcommerceBundle\Entity\Product';
    const ENTITY_FIELD = 'code';

    private static $length = 3;

    /**
     * @var EntityManager
     */
    private static $em;

    public static function setEntityManager(EntityManager $em)
    {
        self::$em = $em;
    }

    /**
     * @param Product $product
     *
     * @return string
     *
     * @throws InvalidEntityCodeException
     */
    public static function generate($product)
    {
        // TODO: improve this (fixed length...) and handle code unicity
        return strtoupper($product->getName());
    }

    /**
     * @param string  $code
     * @param Product $product
     *
     * @return bool
     *
     * @todo   ...
     */
    public static function validate($code, $product = null)
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
