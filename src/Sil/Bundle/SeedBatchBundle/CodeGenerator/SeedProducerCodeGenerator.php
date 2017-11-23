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
use Sil\Bundle\CRMBundle\Entity\OrganismInterface;

class SeedProducerCodeGenerator implements CodeGeneratorInterface
{
    public static $ENTITY_CLASS;
    public static $ENTITY_FIELD = 'seedProducerCode';

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
     * @param OrganismInterface $organism
     *
     * @return string
     */
    public static function generate($organism)
    {
        if (!$organism->isSeedProducer()) {
            return null;
        }

        $length = self::$length;
        $name = $organism->getName();
        if ($organism->isIndividual()) {
            $positions = $organism->getPositions();
            if ($positions && $positions->count() > 0) {
                $contact = $positions[0]->getContact();
                if ($contact->getName()) {
                    $name = $contact->getName();
                } elseif ($contact->getFirstname()) {
                    $name = $contact->getFirstname();
                }
            }
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

        if (self::isCodeUnique($code, $organism)) {
            return $code;
        }

        // XX1 ... XX9
        for ($i = 1; $i < 10; ++$i) {
            $code = sprintf('%s%d', substr($code, 0, $length - 1), $i);
            if (self::isCodeUnique($code, $organism)) {
                return $code;
            }
        }

        // X01 ... X99
        for ($i = 1; $i < 100; ++$i) {
            $code = sprintf('%s%02d', substr($code, 0, $length - 2), $i);
            if (self::isCodeUnique($code, $organism)) {
                return $code;
            }
        }

        return '';
    }

    /**
     * @param string            $code
     * @param OrganismInterface $organism
     *
     * @return bool
     */
    public static function validate($code, $organism = null)
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
     * @param string            $code
     * @param OrganismInterface $organism
     *
     * @return bool
     */
    private static function isCodeUnique($code, OrganismInterface $organism)
    {
        $repo = self::$em->getRepository(OrganismInterface::class);
        $query = $repo->createQueryBuilder('o')
            ->where('o.seedProducerCode = :code')
            ->setParameters(['code' => $code]);
        if ($organism->getId()) {
            $query->andWhere('o.id != :id')->setParameter('id', $organism->getId());
        }
        $result = $query->getQuery()->setMaxResults(1)->getOneOrNullResult();

        return $result == null;
    }
}
