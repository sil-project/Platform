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

namespace Sil\Bundle\SeedBatchBundle\CodeGenerator;

use Doctrine\ORM\EntityManager;
use Blast\Bundle\CoreBundle\CodeGenerator\CodeGeneratorInterface;
use Blast\Bundle\CoreBundle\Exception\InvalidEntityCodeException;
use Sil\Bundle\SeedBatchBundle\Entity\Plot;

class PlotCodeGenerator implements CodeGeneratorInterface
{
    const ENTITY_CLASS = 'Sil\Bundle\SeedBatchBundle\Entity\Plot';
    const ENTITY_FIELD = 'code';

    private static $length = 2;

    /**
     * @var EntityManager
     */
    private static $em;

    public static function setEntityManager(EntityManager $em)
    {
        self::$em = $em;
    }

    /**
     * @param Plot $plot
     *
     * @return string
     *
     * @throws InvalidEntityCodeException
     */
    public static function generate($plot)
    {
        $producer = $plot->getProducer();
        if (!$producer) {
            throw new InvalidEntityCodeException('sil.error.missing_producer');
        }
        $repo = self::$em->getRepository(Plot::class);
        $regexp = sprintf('^(\d{%d})$', self::$length);
        $res = $repo->createQueryBuilder('p')
            ->select("SUBSTRING(p.code, '$regexp') AS code")
            ->andWhere("SUBSTRING(p.code, '$regexp') != ''")
            ->andWhere('p.producer = :producer')
            ->setMaxResults(1)
            ->addOrderBy('code', 'desc')
            ->setParameter('producer', $producer)
            ->getQuery()
            ->getScalarResult()
        ;
        $max = $res ? (int) $res[0]['code'] : 0;

        return sprintf('%0' . self::$length . 'd', $max + 1);
    }

    /**
     * @param string $code
     * @param Plot   $plot
     *
     * @return bool
     */
    public static function validate($code, $plot = null)
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
     * @param string $code
     * @param Plot   $plot
     *
     * @return bool
     */
    private static function isCodeUnique($code, Plot $plot)
    {
        $repo = self::$em->getRepository(Plot::class);
        $query = $repo->createQueryBuilder('o')
            ->where('o.seedProducerCode = :code')
            ->setParameters(['code' => $code]);
        if ($plot->getId()) {
            $query->andWhere('o.id != :id')->setParameter('id', $plot->getId());
        }
        $result = $query->getQuery()->setMaxResults(1)->getOneOrNullResult();

        return $result == null;
    }
}
