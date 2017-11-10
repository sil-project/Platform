<?php

/*
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
use Sil\Bundle\SeedBatchBundle\Entity\SeedFarm;

class SeedFarmCodeGenerator implements CodeGeneratorInterface
{
    const ENTITY_CLASS = 'Sil\Bundle\SeedBatchBundle\Entity\SeedFarm';
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
     * @param SeedFarm $seedFarm
     *
     * @return string
     *
     * @throws InvalidEntityCodeException
     */
    public static function generate($seedFarm)
    {
        $length = self::$length;
        $name = $seedFarm->getName();
        if (!$name) {
            throw new InvalidEntityCodeException('sil.error.missing_seed_farm_name');
        }
        // Unaccent, remove marks and punctuation, upper case
        $translit = transliterator_transliterate(
            'Any-Latin; Latin-ASCII; [:Nonspacing Mark:] Remove; [:Punctuation:] Remove; Upper();',
            $name
        );

        // Remove everything that is not a letter or a digit
        $cleaned = preg_replace('/[^A-Z0-9]/', '', $translit);

        // first chars of name, right padded with "X" if necessary
        $code = str_pad(substr($cleaned, 0, $length), $length, 'X');

        if (self::isCodeUnique($code, $seedFarm)) {
            return $code;
        }

        // XX1 ... XX9
        for ($i = 1; $i < 10; ++$i) {
            $code = sprintf('%s%d', substr($code, 0, $length - 1), $i);
            if (self::isCodeUnique($code, $seedFarm)) {
                return $code;
            }
        }

        // X01 ... X99
        for ($i = 1; $i < 100; ++$i) {
            $code = sprintf('%s%02d', substr($code, 0, $length - 2), $i);
            if (self::isCodeUnique($code, $seedFarm)) {
                return $code;
            }
        }

        return '';
    }

    /**
     * @param string   $code
     * @param SeedFarm $seedFarm
     *
     * @return bool
     */
    public static function validate($code, $seedFarm = null)
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

    /**
     * @param string   $code
     * @param SeedFarm $seedFarm
     *
     * @return bool
     */
    private static function isCodeUnique($code, SeedFarm $seedFarm)
    {
        $repo = self::$em->getRepository(SeedFarm::class);
        $query = $repo->createQueryBuilder('o')
            ->where('o.code = :code')
            ->setParameters(['code' => $code]);
        if ($seedFarm->getId()) {
            $query->andWhere('o.id != :id')->setParameter('id', $seedFarm->getId());
        }
        $result = $query->getQuery()->setMaxResults(1)->getOneOrNullResult();

        return $result == null;
    }
}
