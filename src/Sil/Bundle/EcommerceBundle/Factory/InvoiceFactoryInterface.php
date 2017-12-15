<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Factory;

use Sil\Bundle\EcommerceBundle\Entity\Invoice;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
interface InvoiceFactoryInterface extends FactoryInterface
{
    /**
     * @param OrderInterface $order
     *
     * @return Invoice
     */
    public function createForOrder(OrderInterface $order, $type = Invoice::TYPE_DEBIT);
}
