<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Services;

use Doctrine\ORM\EntityManager;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use SM\Factory\Factory;
use Sylius\Component\Order\Processor\CompositeOrderProcessor;
use Sil\Bundle\EcommerceBundle\StateMachine\OrderTransitions;

/**
 * Manage order item quantity.
 *
 * @author Romain SANCHEZ <romain.sanchez@libre-informatique.fr>
 */
class OrderItemUpdater
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var OrderItemQuantityModifierInterface
     */
    private $orderItemQuantityModifier;
    /**
     * @var MoneyFormatterInterface
     */
    private $moneyFormatter;
    /**
     * @var string
     */
    private $orderItemClass;

    /**
     * @var Factory
     */
    private $smFactory;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var CompositeOrderProcessor
     */
    private $orderProcessor;

    /**
     * @param EntityManager                      $em
     * @param OrderItemQuantityModifierInterface $quantityModifier
     * @param MoneyFormatterInterface            $moneyFormatter
     * @param string                             $orderItemClass
     * @param Factory                            $smFactory
     * @param TranslatorInterface                $translator
     * @param CompositeOrderProcessor            $orderProcessor
     */
    public function __construct(
        EntityManager $em,
        OrderItemQuantityModifierInterface $quantityModifier,
        MoneyFormatterInterface $moneyFormatter,
        $orderItemClass,
        Factory $smFactory,
        TranslatorInterface $translator,
        CompositeOrderProcessor $orderProcessor
    ) {
        $this->em = $em;
        $this->orderItemQuantityModifier = $quantityModifier;
        $this->moneyFormatter = $moneyFormatter;
        $this->orderItemClass = $orderItemClass;
        $this->smFactory = $smFactory;
        $this->translator = $translator;
        $this->orderProcessor = $orderProcessor;
    }

    /**
     * @param string $orderId
     * @param string $itemId
     * @param bool   $isAddition
     * @param int    $quantity
     *
     * @return array
     */
    public function updateItemCount($orderId, $itemId, $isAddition, $stepQuantity = 1)
    {
        $remove = false;
        $lastItem = false;
        $orderRepo = $this->em->getRepository('SilEcommerceBundle:Order');
        $itemRepo = $this->em->getRepository($this->orderItemClass);

        $order = $orderRepo->find($orderId);
        $item = $itemRepo->find($itemId);

        $orderStateMachine = $this->smFactory->get($order, 'sylius_order');

        if (!$orderStateMachine->can(OrderTransitions::TRANSITION_CONFIRM)) {
            return [
                'lastItem' => true,
                'message'  => $this->translator->trans('cannot_edit_order_because_of_state', [], 'SonataCoreBundle'),
            ];
        } else {
            if (!$item->isBulk()) {
                $quantity = ($isAddition ? $item->getQuantity() + $stepQuantity : $item->getQuantity() - $stepQuantity);
            } else {
                $quantity = ($isAddition ? $stepQuantity : 0);
            }

            if ($quantity < 1) {
                if ($order->countItems() < 2) {
                    $lastItem = true;
                } else {
                    $order->removeItem($item);
                    $remove = true;
                }
            } else {
                $this->orderItemQuantityModifier->modify($item, $quantity);
                $item->setQuantity($quantity);
                $item->recalculateUnitsTotal();
            }

            $order->recalculateItemsTotal();
            $this->orderProcessor->process($order);

            $this->em->persist($order);
            $this->em->flush();

            return $this->formatArray($order, $item, $remove, $lastItem);
        }
    }

    /**
     * @param string $order
     * @param string $item
     *
     * @return array
     */
    private function formatArray($order, $item, $remove = false, $lastItem = true)
    {
        return [
            'remove'   => $remove,
            'lastItem' => $lastItem,
            'item'     => [
                'quantity' => $item->getQuantity(),
                'total'    => $this->moneyFormatter->format(
                    $item->getTotal(),
                    $order->getCurrencyCode(),
                    $order->getLocaleCode()
                ),
                'subtotal' => $this->moneyFormatter->format(
                    $item->getSubTotal(),
                    $order->getCurrencyCode(),
                    $order->getLocaleCode()
                ),
            ],
            'order' => [
                'total' => $this->moneyFormatter->format(
                    $order->getTotal(),
                    $order->getCurrencyCode(),
                    $order->getLocaleCode()
                ),
                'items-total' => $this->moneyFormatter->format(
                    $order->getItemsTotal(),
                    $order->getCurrencyCode(),
                    $order->getLocaleCode()
                ),
            ],
            'payments' => $this->getPaymentsTotals($order),
        ];
    }

    private function getPaymentsTotals($order)
    {
        $paiements = [];

        foreach ($order->getPayments() as $payment) {
            $paiements[$payment->getId()] = $this->moneyFormatter->format(
                $payment->getAmount(),
                $order->getCurrencyCode(),
                $order->getLocaleCode()
            );
        }

        return $paiements;
    }
}
