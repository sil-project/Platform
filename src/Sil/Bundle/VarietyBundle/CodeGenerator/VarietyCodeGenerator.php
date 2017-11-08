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

namespace Sil\Bundle\VarietyBundle\CodeGenerator;

use Doctrine\ORM\EntityManager;
use Blast\Bundle\CoreBundle\CodeGenerator\CodeGeneratorInterface;
use Sil\Bundle\VarietyBundle\Entity\Variety;
use Blast\Bundle\CoreBundle\Exception\InvalidEntityCodeException;

class VarietyCodeGenerator implements CodeGeneratorInterface
{
    /**
     * @var EntityManager
     */
    private static $em;

    const ENTITY_CLASS = 'Sil\Bundle\VarietyBundle\Entity\Variety';
    const ENTITY_FIELD = 'code';

    public static function setEntityManager(EntityManager $em)
    {
        self::$em = $em;
    }

    /**
     * @param Variety    $variety
     * @param array|null $existingCodes if null, existing codes will be fetched from database
     *
     * @return string
     *
     * @throws InvalidEntityCodeException
     */
    public static function generate($variety, $existingCodes = null)
    {
        if (!$variety->getName()) {
            throw new InvalidEntityCodeException('sil.error.missing_variety_name');
        }
        if ($variety->getIsStrain() && !$variety->getParent()) {
            throw new InvalidEntityCodeException('sil.error.missing_strain_parent');
        }
        if (!$variety->getSpecies()) {
            throw new InvalidEntityCodeException('sil.error.missing_species');
        }
        $name = preg_replace('/^' . $variety->getSpecies()->getName() . ' /', '', $variety->getName());

        if ($variety->getIsStrain()) {
            // Prepend with parent name
            $prefix = $variety->getParent()->getCode();
            // Unaccent, remove marks and punctuation, lower case
            $translit = transliterator_transliterate(
                'Any-Latin; Latin-ASCII; [:Nonspacing Mark:] Remove; [:Punctuation:] Remove; Lower();',
                $name
            );
            $length = 2;
            $pad = 'x';
        } else {
            $prefix = '';
            // Unaccent, remove marks and punctuation, upper case
            $translit = transliterator_transliterate(
                'Any-Latin; Latin-ASCII; [:Nonspacing Mark:] Remove; [:Punctuation:] Remove; Upper();',
                $name
            );
            $length = 3;
            $pad = 'X';
        }

        // Remove everything that is not a letter or a digit
        $cleaned = preg_replace('/[^A-Z0-9]/', '', $translit);

        // first chars of name, right padded with "X" if necessary
        $code = $prefix . str_pad(substr($cleaned, 0, $length), $length, $pad);

        if (self::isCodeUnique($code, $variety, $existingCodes)) {
            return $code;
        }

        // XX1 ... XX9
        for ($i = 1; $i < 10; ++$i) {
            $code = $prefix . sprintf('%s%d', substr($code, 0, $length - 1), $i);
            if (self::isCodeUnique($code, $variety, $existingCodes)) {
                return $code;
            }
        }

        // X01 ... X99
        for ($i = 1; $i < 100; ++$i) {
            $code = $prefix . sprintf('%s%02d', substr($code, 0, $length - 2), $i);
            if (self::isCodeUnique($code, $variety, $existingCodes)) {
                return $code;
            }
        }

        // 001 ... 999
        // TODO: find a better solution !!
        if (!$variety->getIsStrain()) {
            for ($i = 1; $i < 1000; ++$i) {
                $code = sprintf('%03d', $i);
                if (self::isCodeUnique($code, $variety, $existingCodes)) {
                    return $code;
                }
            }
        }

        return '';
    }

    /**
     * @param string  $code
     * @param Variety $variety
     *
     * @return bool
     */
    public static function validate($code, $variety = null)
    {
        if ($variety->getIsStrain()) {
            if ($variety->getParent() && substr($code, 0, 3) != $variety->getParent()->getCode()) {
                return false;
            }

            return preg_match('/^[A-Z0-9]{3}[a-z0-9]{2}$/', $code) > 0;
        } else {
            return preg_match('/^[A-Z0-9]{3}$/', $code) > 0;
        }
    }

    /**
     * @param string     $code
     * @param Variety    $variety
     * @param array|null $existingCodes if null, existing codes will be fetched from database
     *
     * @return bool
     */
    private static function isCodeUnique($code, $variety, $existingCodes = null)
    {
        if (null !== $existingCodes) {
            return !in_array($code, $existingCodes);
        }

        $repo = self::$em->getRepository('Sil\Bundle\VarietyBundle\Entity\Variety');
        $query = $repo->createQueryBuilder('v')
               ->where('v.code = :code')
               ->setParameter('code', $code);
        if ($variety->getId()) {
            $query->andWhere('v.id != :id')
                ->setParameter('id', $variety->getId());
        }
        $result = $query->getQuery()->setMaxResults(1)->getOneOrNullResult();

        return $result == null;
    }

    public static function getHelp()
    {
        return '3 chars (A-Z and/or 0-9) + 2 chars (a-z and/or 0-9) for the strain';
    }
}
