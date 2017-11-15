<?php

/*
 * This file is part of the Lisem Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\CodeGenerator;

use Blast\Bundle\CoreBundle\Exception\InvalidEntityCodeException;
use Sil\Bundle\EcommerceBundle\Entity\ProductVariant;
use Sil\Bundle\EcommerceBundle\CodeGenerator\ProductVariantCodeGenerator as BaseCodeGenerator;
use Symfony\Component\HttpFoundation\RequestStack;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class ProductVariantCodeGenerator extends BaseCodeGenerator
{
    /**
     * @var RequestStack
     */
    private static $requestStack;

    /**
     * @var EntityRepository
     */
    private static $packagingRepo;

    public static function setRequestStack(RequestStack $requestStack)
    {
        self::$requestStack = $requestStack;
    }

    public static function getRequestStack()
    {
        return self::$requestStack;
    }

    public static function setPackagingRepo(EntityRepository $packagingRepo)
    {
        self::$packagingRepo = $packagingRepo;
    }

    public static function getPackagingRepo()
    {
        return self::$packagingRepo;
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
        $request = self::$requestStack;

        if ($productVariant->getSeedBatches() === null || $productVariant->getPackaging() === null) {
            $formName = $request->getCurrentRequest()->get('uniqid', null);

            $seedBatch = $request->getCurrentRequest()->get(sprintf('%s_%s', $formName, 'seedBatch'), null);
            $packaging = $request->getCurrentRequest()->get(sprintf('%s_%s', $formName, 'packaging'), null);

            if ($seedBatch && $packaging) {
                $batch = self::$em->getRepository('SilSeedBatchBundle:SeedBatch')->find($seedBatch);
                $packaging = self::$packagingRepo->find($packaging);

                return sprintf('%s-%s', $batch->getCode(), $packaging->getCode());
            }
        }

        if (!$seedBatch = $productVariant->getSeedBatches()) {
            throw new InvalidEntityCodeException('sil.error.missing_seed_batch');
        }
        if (!$varietyCode = $productVariant->getProduct()->getCode()) {
            throw new InvalidEntityCodeException('sil.error.missing_variety_code');
        }
        if (!$packaging = $productVariant->getPackaging()) {
            throw new InvalidEntityCodeException('sil.error.missing_packaging');
        }
        if (!$packagingCode = $packaging->getCode()) {
            throw new InvalidEntityCodeException('sil.error.missing_packaging_code');
        }

        // Check unicity of generated code

        $newCode = sprintf('%s-%s', $varietyCode, $packagingCode);

        $sql = 'SELECT MAX(p.code) as lastcode FROM ' . self::$em->getClassMetadata(get_class($productVariant))->getTableName() . ' p WHERE p.code ILIKE ?;';

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('lastcode', 'lastcode');
        $query = self::$em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $newCode . '%');

        $existingCode = $query->getSingleScalarResult();

        if ($existingCode !== null) {
            $newCode = ++$existingCode;
        }

        return $newCode;
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
