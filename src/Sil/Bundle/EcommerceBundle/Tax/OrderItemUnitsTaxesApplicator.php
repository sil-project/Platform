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

namespace Sil\Bundle\EcommerceBundle\Tax;

use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemUnitInterface;
use Sylius\Component\Core\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Taxation\Calculator\CalculatorInterface;
use Sylius\Component\Taxation\Resolver\TaxRateResolverInterface;

class OrderItemUnitsTaxesApplicator implements OrderTaxesApplicatorInterface
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
     * @var TaxRateResolverInterface
     */
    private $taxRateResolver;

    /**
     * @param CalculatorInterface        $calculator
     * @param AdjustmentFactoryInterface $adjustmentFactory
     * @param TaxRateResolverInterface   $taxRateResolver
     */
    public function __construct(
        CalculatorInterface $calculator,
        AdjustmentFactoryInterface $adjustmentFactory,
        TaxRateResolverInterface $taxRateResolver
    ) {
        $this->calculator = $calculator;
        $this->adjustmentFactory = $adjustmentFactory;
        $this->taxRateResolver = $taxRateResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(OrderInterface $order, ZoneInterface $zone): void
    {
        foreach ($order->getItems() as $item) {
            $taxRate = $this->taxRateResolver->resolve($item->getVariant(), ['zone' => $zone]);
            if (null === $taxRate) {
                continue;
            }

            if ($item->isBulk()) {
                $taxAmount = $this->calculator->calculate($item->getTotal(), $taxRate);
                $this->addItemTaxAdjustment($item, (int) $taxAmount, $taxRate->getLabel(), $taxRate->isIncludedInPrice());
                break; // If item is bulk, process only first order item unit (it should be)
            }

            foreach ($item->getUnits() as $unit) {
                $taxAmount = $this->calculator->calculate($unit->getTotal(), $taxRate);
                if (0.00 === $taxAmount) {
                    continue;
                }

                $this->addTaxAdjustment($unit, (int) $taxAmount, $taxRate->getLabel(), $taxRate->isIncludedInPrice());
            }
        }
    }

    /**
     * @param OrderItemUnitInterface $unit
     * @param int                    $taxAmount
     * @param string                 $label
     * @param bool                   $included
     */
    private function addTaxAdjustment(OrderItemUnitInterface $unit, int $taxAmount, string $label, bool $included)
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
