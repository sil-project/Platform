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

namespace Sil\Bundle\SeedBatchBundle\CodeGenerator;

use Doctrine\ORM\EntityManager;
use Blast\Bundle\CoreBundle\CodeGenerator\CodeGeneratorInterface;
use Blast\Bundle\CoreBundle\Exception\InvalidEntityCodeException;
use Sil\Bundle\SeedBatchBundle\Entity\SeedBatch;

class SeedBatchCodeGenerator implements CodeGeneratorInterface
{
    const ENTITY_CLASS = 'Sil\Bundle\SeedBatchBundle\Entity\SeedBatch';
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
     * @param SeedBatch $seedBatch
     *
     * @return string
     *
     * @throws InvalidEntityCodeException
     */
    public static function generate($seedBatch)
    {
        if (!$seedFarm = $seedBatch->getSeedFarm()) {
            throw new InvalidEntityCodeException('sil.error.missing_seed_farm');
        }
        if (!$seedFarmCode = $seedFarm->getCode()) {
            throw new InvalidEntityCodeException('sil.error.missing_seed_farm_code');
        }
        $variety = $seedBatch->getVariety();
        if (!$variety) {
            throw new InvalidEntityCodeException('sil.error.missing_variety');
        }
        if (!$varietyCode = $variety->getCode()) {
            throw new InvalidEntityCodeException('sil.error.missing_variety_code');
        }
        $species = $variety->getSpecies();
        if (!$species) {
            throw new InvalidEntityCodeException('sil.error.missing_species');
        }
        if (!$speciesCode = $species->getCode()) {
            throw new InvalidEntityCodeException('sil.error.missing_species_code');
        }
        $producer = $seedBatch->getProducer();
        if (!$producer) {
            throw new InvalidEntityCodeException('sil.error.missing_producer');
        }
        if (!$producerCode = $producer->getSeedProducerCode()) {
            throw new InvalidEntityCodeException('sil.error.missing_producer_code');
        }
        $productionYear = (int) $seedBatch->getProductionYear();
        if (!$productionYear) {
            throw new InvalidEntityCodeException('sil.error.missing_production_year');
        }
        // if ($productionYear < 2000 || $productionYear > 2099) {
        if ($productionYear < 1) {
            throw new InvalidEntityCodeException('sil.error.invalid_production_year');
        }
        // TODO: test if year is too far in the future ?

        $batchNumber = $seedBatch->getBatchNumber();
        if (!$batchNumber) {
            throw new InvalidEntityCodeException('sil.error.missing_batch_number');
        }
        // if ($batchNumber < 1 || $batchNumber > 99) {
        if ($batchNumber < 1) {
            throw new InvalidEntityCodeException('sil.error.invalid_batch_number');
        }

        return sprintf(
            '%s-%s%s-%s-%02d-%02d',
            $seedFarmCode,
            $speciesCode,
            $varietyCode,
            $producerCode,
            $productionYear % 100, // - 2000,
            $batchNumber % 100
        );
    }

    /**
     * @param string   $code
     * @param SeedFarm $seedBatch
     *
     * @return bool
     */
    public static function validate($code, $seedBatch = null)
    {
        return preg_match('/^[A-Z0-9]{' . self::$length . '}$/', $code);
    }

    /**
     * @return string
     */
    public static function getHelp()
    {
        return self::$length . ' chars (upper case letters and/or digits)';
    }
}
