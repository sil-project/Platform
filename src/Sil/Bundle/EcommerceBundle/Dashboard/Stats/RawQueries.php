<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Dashboard\Stats;

use Sil\Bundle\EcommerceBundle\Entity\Order;
use Sil\Bundle\EcommerceBundle\Entity\OrderItem;
use Sylius\Component\Order\Model\Adjustment;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sil\Bundle\EcommerceBundle\Entity\Payment;

class RawQueries extends AbstractStats
{
    /**
     * @var string
     */
    private $orderTableName;

    /**
     * @var string
     */
    private $orderItemsTableName;

    /**
     * @var string
     */
    private $adjustmentTableName;

    /**
     * @var string
     */
    private $paymentTableName;

    /**
     * @var string
     */
    private $orderState = Order::STATE_FULFILLED;

    /**
     * @var string
     */
    private $adjustmentTypeTax = AdjustmentInterface::TAX_ADJUSTMENT;

    public function getData(array $parameters = []): array
    {
        // Do nothing
    }

    private function init(): void
    {
        if (!$this->orderTableName) {
            $this->orderTableName = $this->doctrine->getManager()->getClassMetadata(Order::class)->getTableName();
        }

        if (!$this->orderItemsTableName) {
            $this->orderItemsTableName = $this->doctrine->getManager()->getClassMetadata(OrderItem::class)->getTableName();
        }

        if (!$this->adjustmentTableName) {
            $this->adjustmentTableName = $this->doctrine->getManager()->getClassMetadata(Adjustment::class)->getTableName();
        }

        if (!$this->paymentTableName) {
            $this->paymentTableName = $this->doctrine->getManager()->getClassMetadata(Payment::class)->getTableName();
        }
    }

    /**
     * @return array
     */
    public function getSalesForRunningYear(): array
    {
        $this->init();

        $sql = "
            SELECT
                date_part('year', p.updated_at) || '-' || date_part('month', p.updated_at) || '-01' AS x,
                --date_part('year', p.updated_at) || '-' || date_part('month', p.updated_at) || '-' || date_part('day', p.updated_at) AS x,
                sum(o.total) / 100 AS y
                --p.updated_at::date AS originalDate
            FROM
                " . $this->orderTableName . ' o
            LEFT JOIN
                ' . $this->paymentTableName . " p
                ON
                    p.order_id = o.id
            WHERE
                o.state = :orderState
                AND
                p.updated_at > date('now') - interval '1 year'
            GROUP BY
                x
            ORDER BY
                x ASC
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue('orderState', $this->orderState);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @return array
     */
    public function getSalesForCurrentYear(): array
    {
        $this->init();

        $sql = "
            SELECT
                'sil.ecommercebundle.sales_of_current_year' as label,
                sum(o.total) as value
            FROM
                " . $this->orderTableName . ' o
            WHERE
                o.state = :orderState
                AND
                extract(isoyear from o.checkout_completed_at) = extract(isoyear from CURRENT_TIMESTAMP)
        ';
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue('orderState', $this->orderState);

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * @return array
     */
    public function getTaxesForCurrentYear(): array
    {
        $this->init();

        $sql = "
            SELECT
                'sil.ecommercebundle.taxes_of_current_year' as label,
                sum(a.amount) as value
            FROM
                " . $this->orderTableName . ' o
            LEFT JOIN
                ' . $this->orderItemsTableName . ' oi
                ON
                    oi.order_id = o.id
            LEFT JOIN
                ' . $this->adjustmentTableName . ' a
                ON
                    a.order_item_id = oi.id
                    AND
                    a.type = :adjustmentType
            WHERE
                o.state = :orderState
                AND
                extract(isoyear from o.checkout_completed_at) = extract(isoyear from CURRENT_TIMESTAMP)
        ';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue('orderState', $this->orderState);
        $stmt->bindValue('adjustmentType', $this->adjustmentTypeTax);

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * @return array
     */
    public function getTotalSales(): array
    {
        $this->init();

        $sql = "
            SELECT
                'sil.ecommercebundle.total_sales' as label,
                sum(o.total) as value
            FROM
                " . $this->orderTableName . ' o
            WHERE
                o.state = :orderState
        ';
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue('orderState', $this->orderState);

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * @return array
     */
    public function getTotalTaxes(): array
    {
        $this->init();

        $sql = "
            SELECT
                'sil.ecommercebundle.total_taxes' as label,
                sum(a.amount) as value
            FROM
                " . $this->orderTableName . ' o
            LEFT JOIN
                ' . $this->orderItemsTableName . ' oi
                ON
                    oi.order_id = o.id
            LEFT JOIN
                ' . $this->adjustmentTableName . ' a
                ON
                    a.order_item_id = oi.id
                    AND
                    a.type = :adjustmentType
            WHERE
                o.state = :orderState
        ';

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue('orderState', $this->orderState);
        $stmt->bindValue('adjustmentType', $this->adjustmentTypeTax);

        $stmt->execute();

        return $stmt->fetch();
    }
}
