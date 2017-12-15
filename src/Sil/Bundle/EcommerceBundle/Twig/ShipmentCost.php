<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Twig;

use Sylius\Component\Shipping\Calculator\DelegatingCalculatorInterface;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatter;

class ShipmentCost extends \Twig_Extension
{
    /**
     * @var DelegatingCalculatorInterface
     */
    private $shippingCalculator;

    /**
     * @var MoneyFormatter
     */
    private $moneyFormater;

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getShipmentCost', [$this, 'getShipmentCost'], ['is_safe'=>['html']]),
        ];
    }

    public function getShipmentCost($shipment)
    {
        $cost = $this->shippingCalculator->calculate($shipment);

        return $this->moneyFormater->format($cost, $shipment->getOrder()->getCurrencyCode(), $shipment->getOrder()->getLocaleCode());
    }

    /**
     * @param DelegatingCalculatorInterface shippingCalculator
     */
    public function setShippingCalculator(DelegatingCalculatorInterface $shippingCalculator): void
    {
        $this->shippingCalculator = $shippingCalculator;
    }

    /**
     * @param MoneyFormatter moneyFormater
     */
    public function setMoneyFormater(MoneyFormatter $moneyFormater): void
    {
        $this->moneyFormater = $moneyFormater;
    }
}
