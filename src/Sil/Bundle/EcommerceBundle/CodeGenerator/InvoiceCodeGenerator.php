<?php

/*
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
use Sil\Bundle\EcommerceBundle\Entity\Invoice;

/**
 * Sequencial invoice number generator (9 digits).
 *
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class InvoiceCodeGenerator implements CodeGeneratorInterface
{
    const ENTITY_CLASS = 'Sil\Bundle\EcommerceBundle\Entity\Invoice';
    const ENTITY_FIELD = 'number';

    // TODO: this should be in app configuration:
    public static $codePrefix = '';
    public static $codeLength = 9;

    /**
     * @var EntityManager
     */
    private static $em;

    public static function setEntityManager(EntityManager $em)
    {
        self::$em = $em;
    }

    /**
     * @param Invoice $invoice
     *
     * @return string
     */
    public static function generate($invoice)
    {
        if ($invoice->getNumber() !== null) {
            return $invoice->getNumber();
        }

        $repo = self::$em->getRepository(Invoice::class);
        $regexp = sprintf('^%s(\d{%d})$', self::$codePrefix, self::$codeLength);
        $res = $repo->createQueryBuilder('i')
            ->select('i.number')
            ->where("SUBSTRING(i.number, '$regexp') != ''")
            ->setMaxResults(1)
            ->addOrderBy('i.number', 'desc')
            ->getQuery()
            ->getScalarResult();
        $max = $res ? (int) $res[0]['number'] : 0;

        return sprintf('%s%0' . self::$codeLength . 'd', self::$codePrefix, $max + 1);
    }

    /**
     * @param string  $number
     * @param Invoice $invoice
     *
     * @return bool
     */
    public static function validate($number, $invoice = null)
    {
        $regexp = sprintf('/^%s(\d{%d})$/', self::$codePrefix, self::$codeLength);

        return preg_match($regexp, $number);
    }

    /**
     * @return string
     */
    public static function getHelp()
    {
        return 'Sequential numbering for invoices, six digits';
    }
}
