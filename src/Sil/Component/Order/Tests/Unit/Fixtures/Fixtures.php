<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Order\Tests\Unit\Fixtures;

use Sil\Component\Account\Model\Account;
use Sil\Component\Account\Model\AccountInterface;
use Sil\Component\Order\Model\AdjustmentType;
use Sil\Component\Order\Model\Order;
use Sil\Component\Order\Model\OrderAdjustment;
use Sil\Component\Order\Model\OrderCode;
use Sil\Component\Order\Model\OrderInterface;
use Sil\Component\Order\Model\OrderItem;
use Sil\Component\Order\Model\OrderItemAdjustment;
use Sil\Component\Order\Model\Price;
use Sil\Component\Order\Repository\OrderRepositoryInterface;
use Sil\Component\Order\Tests\Unit\Repository\OrderRepository;
use Sil\Component\Order\Tests\Unit\Repository\CurrencyRepository;
use Sil\Component\Uom\Model\UomQty;
use Sil\Component\Currency\Model\Currency;
use Sil\Component\Currency\Model\CurrencyInterface;
use Sil\Component\Currency\Repository\CurrencyRepositoryInterface;
use Sil\Component\Uom\Repository\UomRepositoryInterface;
use Sil\Component\Uom\Repository\UomTypeRepositoryInterface;
use Sil\Component\Uom\Tests\Unit\Fixture\UomFixturesTrait;

class Fixtures
{
    use UomFixturesTrait;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var CurrencyRepositoryInterface
     */
    private $currencyRepository;

    private $rawData = [];

    public function __construct()
    {
        $this->rawData = [
            'Orders' => [
                'FA00000001' => [
                    'code'     => 'FA00000001',
                    'account'  => 'ACCOUNT001',
                    'currency' => 'EUR',
                    'items'    => [
                        'Item One' => [
                            'label'                        => 'Item One',
                            'quantity'                     => '2',
                            'unitPrice'                    => '25.99 EUR',
                            'expectedTotalWithAdjustments' => 54.8389,
                            'expectedTotal'                => 51.98,
                            'adjustments'                  => [
                                [
                                    'strategy'      => 'Increase',
                                    'type'          => 'rate',
                                    'label'         => 'VAT 5.5%',
                                    'value'         => 0.055,
                                    'expectedTotal' => 2.8588999999999998,
                                ],
                            ],
                        ],
                        'Item Two' => [
                            'label'                        => 'Item Two',
                            'quantity'                     => '5',
                            'unitPrice'                    => '185.75 EUR',
                            'expectedTotalWithAdjustments' => 908.75,
                            'expectedTotal'                => 928.75,
                            'adjustments'                  => [
                                [
                                    'strategy'      => 'Neutral',
                                    'type'          => 'rate',
                                    'label'         => 'VAT 20%',
                                    'value'         => 0.2,
                                    'expectedTotal' => 185.75,
                                ],
                                [
                                    'strategy'      => 'Decrease',
                                    'type'          => 'fixedValue',
                                    'label'         => 'OFF -20 €',
                                    'value'         => 20,
                                    'expectedTotal' => -20,
                                ],
                            ],
                        ],
                        'Item Three' => [
                            'label'                        => 'Item Three',
                            'quantity'                     => '4',
                            'unitPrice'                    => '19.99 EUR',
                            'expectedTotalWithAdjustments' => 79.96,
                            'expectedTotal'                => 79.96,
                            'adjustments'                  => [
                                [
                                    'strategy'      => 'Neutral',
                                    'type'          => 'rate',
                                    'label'         => 'VAT 7%',
                                    'value'         => 0.07,
                                    'expectedTotal' => 5.5972,
                                ],
                            ],
                        ],
                    ],
                    'adjustments' => [
                        [
                            'strategy'      => 'Decrease',
                            'type'          => 'rate',
                            'label'         => 'OFF -5% on order',
                            'value'         => 0.05,
                            'expectedTotal' => -52.177445,
                        ],
                    ],
                    'expectedTotalWithAdjustments' => 991.371455,
                    'expectedTotal'                => 1043.5489,
                ],
                'FA00000002' => [
                    'code'     => 'FA00000002',
                    'account'  => 'ACCOUNT002',
                    'currency' => 'USD',
                    'items'    => [
                        'Item Four' => [
                            'label'                        => 'Item Four',
                            'quantity'                     => '1.512 Kg',
                            'unitPrice'                    => '25.99 USD',
                            'expectedTotalWithAdjustments' => 39.29688,
                            'expectedTotal'                => 39.29688,
                        ],
                        'Item Five' => [
                            'label'                        => 'Item Five',
                            'quantity'                     => '2.25 m',
                            'unitPrice'                    => '185.75 USD',
                            'expectedTotalWithAdjustments' => 417.9375,
                            'expectedTotal'                => 417.9375,
                        ],
                    ],
                    'expectedTotalWithAdjustments' => 457.23438,
                    'expectedTotal'                => 457.23438,
                ],
            ],
            'Currencies' => [
                'EUR',
                'USD',
            ],
            'Accounts' => [
                'ACCOUNT001' => [
                    'name' => 'Account 001',
                ],
                'ACCOUNT002' => [
                    'name' => 'Account 002',
                ],
            ],
        ];

        $this->orderRepository = new OrderRepository(OrderInterface::class);

        $this->currencyRepository = new CurrencyRepository(CurrencyInterface::class);

        $this->generateFixtures();
    }

    private function generateFixtures(): void
    {
        $this->loadMassUomFixtures();
        $this->loadLengthUomFixtures();
        $this->loadPiecesUomFixtures();
        $this->loadCurrencies();
        $this->generateOrders();
    }

    private function generateOrders(): void
    {
        foreach ($this->getRawData()['Orders'] as $orderData) {
            $accountData = $this->getRawData()['Accounts'][$orderData['account']];
            $account = new Account($accountData['name'], $orderData['account']);
            $currency = $this->getCurrencyRepository()->getByCode($orderData['currency']);
            $order = new Order(new OrderCode($orderData['code']), $account, $currency);

            foreach ($orderData['items'] as $itemData) {
                $quantity = $this->guessQuantity($itemData['quantity']);
                $price = $this->guessPrice($itemData['unitPrice']);

                $item = new OrderItem($order, $itemData['label'], $quantity, $price);
                $item->expectedTotalWithAdjustments = $itemData['expectedTotalWithAdjustments'];
                $item->expectedTotal = $itemData['expectedTotal'];

                if (isset($itemData['adjustments'])) {
                    $this->loadOrderItemAdjustments($item, $itemData['adjustments']);
                }
            }

            if (isset($orderData['adjustments'])) {
                $this->loadOrderAdjustments($order, $orderData['adjustments']);
            }

            $order->expectedTotalWithAdjustments = $orderData['expectedTotalWithAdjustments'];
            $order->expectedTotal = $orderData['expectedTotal'];

            $this->getOrderRepository()->add($order);
        }
    }

    private function loadOrderItemAdjustments(OrderItem $orderItem, array $itemData): void
    {
        foreach ($itemData as $adjustmentData) {
            $strategyName = '\\Sil\\Component\\Order\Model\\AdjustmentStrategy' . ucfirst(strtolower($adjustmentData['strategy']));
            $strategy = new $strategyName();
            $adjustmentType = AdjustmentType::{$adjustmentData['type']}();
            $adjustment = new OrderItemAdjustment($orderItem, $strategy, $adjustmentType, $adjustmentData['label'], $adjustmentData['value']);
            $adjustment->expectedTotal = $adjustmentData['expectedTotal'];
        }
    }

    private function loadOrderAdjustments(Order $order, array $orderAdjustmentData): void
    {
        foreach ($orderAdjustmentData as $adjustmentData) {
            $strategyName = '\\Sil\\Component\\Order\Model\\AdjustmentStrategy' . ucfirst(strtolower($adjustmentData['strategy']));
            $strategy = new $strategyName();
            $adjustmentType = AdjustmentType::{$adjustmentData['type']}();
            $adjustment = new OrderAdjustment($order, $strategy, $adjustmentType, $adjustmentData['label'], $adjustmentData['value']);
            $adjustment->expectedTotal = $adjustmentData['expectedTotal'];
        }
    }

    private function loadCurrencies(): void
    {
        foreach ($this->getRawData()['Currencies'] as $currencyCode) {
            $currency = new Currency($currencyCode);
            $this->getCurrencyRepository()->add($currency);
        }
    }

    private function guessQuantity($quantityString): UomQty
    {
        preg_match('/([0-9\.]*)(?:\ ([a-zA-Z]*))?/', $quantityString, $matches);

        array_shift($matches);
        if (count($matches) > 1) {
            list($qtyValue, $qtyUnit) = $matches;
            $uom = $this->getUomRepository()->findOneBy(['name' => $qtyUnit]);
        } else {
            $qtyValue = $matches[0];
            $uom = $this->getUomRepository()->findOneBy(['name' => 'piece']);
        }

        return new UomQty($uom, floatval($qtyValue));
    }

    private function guessPrice($priceString): Price
    {
        preg_match('/([0-9\.]*)\ ([A-Z]{3})/', $priceString, $matches);

        array_shift($matches);
        list($priceValue, $priceCurrencyCode) = $matches;
        $currency = $this->getCurrencyRepository()->findOneBy(['code' => $priceCurrencyCode]);

        return new Price($currency, floatval($priceValue));
    }

    /**
     * @return AccountInterface
     */
    public function getDummyAccount(): AccountInterface
    {
        return new Account('Dummy account', 'ACC_DUMMY');
    }

    /**
     * @return OrderRepositoryInterface
     */
    public function getOrderRepository(): OrderRepositoryInterface
    {
        return $this->orderRepository;
    }

    /**
     * @return UomRepositoryInterface
     */
    public function getUomRepository(): UomRepositoryInterface
    {
        return $this->uomRepository;
    }

    /**
     * @return UomTypeRepositoryInterface
     */
    public function getUomTypeRepository(): UomTypeRepositoryInterface
    {
        return $this->uomTypeRepository;
    }

    /**
     * @return CurrencyRepositoryInterface
     */
    public function getCurrencyRepository(): CurrencyRepositoryInterface
    {
        return $this->currencyRepository;
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return $this->rawData;
    }
}
