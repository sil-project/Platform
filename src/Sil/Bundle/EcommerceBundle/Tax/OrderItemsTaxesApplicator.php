<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Tax;

use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Distributor\IntegerDistributorInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemUnitInterface;
use Sylius\Component\Core\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Taxation\Calculator\CalculatorInterface;
use Sylius\Component\Taxation\Resolver\TaxRateResolverInterface;
use Webmozart\Assert\Assert;

class OrderItemsTaxesApplicator implements OrderTaxesApplicatorInterface
{
    /**
     * @var CalculatorInterface
     */
    private $calculator;

    /**
     * @var AdjustmentFactoryInterface
     */
    private $adjustmentFactory;

    /**
     * @var IntegerDistributorInterface
     */
    private $distributor;

    /**
     * @var TaxRateResolverInterface
     */
    private $taxRateResolver;

    /**
     * @param CalculatorInterface         $calculator
     * @param AdjustmentFactoryInterface  $adjustmentFactory
     * @param IntegerDistributorInterface $distributor
     * @param TaxRateResolverInterface    $taxRateResolver
     */
    public function __construct(
        CalculatorInterface $calculator,
        AdjustmentFactoryInterface $adjustmentFactory,
        IntegerDistributorInterface $distributor,
        TaxRateResolverInterface $taxRateResolver
    ) {
        $this->calculator = $calculator;
        $this->adjustmentFactory = $adjustmentFactory;
        $this->distributor = $distributor;
        $this->taxRateResolver = $taxRateResolver;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function apply(OrderInterface $order, ZoneInterface $zone): void
    {
        foreach ($order->getItems() as $item) {
            $quantity = $item->getQuantity();
            Assert::notSame($quantity, 0, 'Cannot apply tax to order item with 0 quantity.');

            $taxRate = $this->taxRateResolver->resolve($item->getVariant(), ['zone' => $zone]);

            if (null === $taxRate) {
                continue;
            }

            $totalTaxAmount = $this->calculator->calculate($item->getTotal(), $taxRate);
            $splitTaxes = $this->distributor->distribute($totalTaxAmount, $quantity);

            if ($item->isBulk()) {
                $this->addItemTaxAdjustment($item, (int) $totalTaxAmount, $taxRate->getLabel(), $taxRate->isIncludedInPrice());

                return; // If item is bulk, process only first order item unit (it should be)
            }

            $i = 0;
            foreach ($item->getUnits() as $unit) {
                if (0 === $splitTaxes[$i]) {
                    continue;
                }

                $this->addAdjustment($unit, $splitTaxes[$i], $taxRate->getLabel(), $taxRate->isIncludedInPrice());
                ++$i;
            }
        }
    }

    /**
     * @param OrderItemUnitInterface $unit
     * @param int                    $taxAmount
     * @param string                 $label
     * @param bool                   $included
     */
    private function addAdjustment(OrderItemUnitInterface $unit, int $taxAmount, string $label, bool $included): void
    {
        $unitTaxAdjustment = $this->adjustmentFactory
            ->createWithData(AdjustmentInterface::TAX_ADJUSTMENT, $label, $taxAmount, $included)
        ;
        $unit->addAdjustment($unitTaxAdjustment);
    }

    /**
     * @param OrderItemUnitInterface $item
     * @param int                    $taxAmount
     * @param string                 $label
     * @param bool                   $included
     */
    private function addItemTaxAdjustment(OrderItemInterface $item, int $taxAmount, string $label, bool $included)
    {
        $unitTaxAdjustment = $this->adjustmentFactory
            ->createWithData(AdjustmentInterface::TAX_ADJUSTMENT, $label, $taxAmount, $included)
        ;
        $item->addAdjustment($unitTaxAdjustment);
    }
}
