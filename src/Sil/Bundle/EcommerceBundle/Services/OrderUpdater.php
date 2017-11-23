<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Services;

use Doctrine\ORM\EntityManager;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Order\Factory\OrderItemUnitFactoryInterface;
use Sylius\Component\Order\Processor\CompositeOrderProcessor;
use SM\Factory\Factory;
use Sil\Bundle\EcommerceBundle\Entity\Product;
use Sil\Bundle\EcommerceBundle\Entity\ProductVariant;
use Sil\Bundle\EcommerceBundle\Entity\OrderInterface;
use Sil\Bundle\EcommerceBundle\Entity\ProductVariantInterface;

/**
 * Add products to existing order.
 *
 * @author Romain SANCHEZ <romain.sanchez@libre-informatique.fr>
 */
class OrderUpdater
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Channel
     */
    private $channel;

    /**
     * @var FactoryInterface
     */
    private $orderItemFactory;

    /**
     * @var OrderItemUnitFactoryInterface
     */
    private $orderItemUnitFactory;

    /**
     * @var Factory
     */
    private $smFactory;

    /**
     * @var CompositeOrderProcessor
     */
    private $orderProcessor;

    /**
     * @param EntityManager                 $em
     * @param ChannelContextInterface       $channelContext
     * @param FactoryInterface              $orderItemFactory
     * @param OrderItemUnitFactoryInterface $orderItemUnitFactory
     * @param Factory                       $smFactory
     * @param CompositeOrderProcessor       $orderProcessor
     */
    public function __construct(
        EntityManager $em,
        ChannelContextInterface $channelContext,
        FactoryInterface $orderItemFactory,
        OrderItemUnitFactoryInterface $orderItemUnitFactory,
        Factory $smFactory,
        CompositeOrderProcessor $orderProcessor
    ) {
        $this->em = $em;
        $this->channel = $channelContext->getChannel();
        $this->orderItemFactory = $orderItemFactory;
        $this->orderItemUnitFactory = $orderItemUnitFactory;
        $this->smFactory = $smFactory;
        $this->orderProcessor = $orderProcessor;
    }

    /**
     * @param string $orderId
     * @param string $variantId
     */
    public function addProduct($orderId, $variantId)
    {
        //Retrieve order
        $order = $this->em
            ->getRepository(OrderInterface::class)
            ->find($orderId);

        $orderStateMachine = $this->smFactory->get($order, 'sylius_order');

        if ($orderStateMachine->getState() === 'cancelled' || $orderStateMachine->getState() === 'fulfilled') {
            $item = null;
        } else {
            //Retrieve product variant
            /** @var ProductVariant $variant * */
            $variant = $this->em
                ->getRepository(ProductVariantInterface::class)
                ->find($variantId);

            $optionCode = $variant->getOptionValues()->first() ? $variant->getOptionValues()->first()->getOption()->getCode() : false;
            $optionValue = $variant->getOptionValues()->first() ? $variant->getOptionValues()->first()->getCode() : false;

            $isBulk = ($optionCode === Product::$PACKAGING_OPTION_CODE && $optionValue === 'BULK');

            //Create new OrderItem
            $item = $this->orderItemFactory->createNew();
            $item->setVariant($variant);
            $item->setOrder($order);
            $item->setBulk($isBulk);

            if ($isBulk) {
                $item->setquantity(999);
            }

            $item->setUnitPrice(
                $variant->getchannelPricingForChannel($this->channel)->getPrice()
            );

            //Create OrderItemUnit from OrderItem
            $this->orderItemUnitFactory->createForItem($item);

            // //Recalculate Order totals
            // $item->recalculateUnitsTotal();
            // $order->recalculateItemstotal();

            $order->getPayments()->clear(); // Avoid payement duplicates

            $this->orderProcessor->process($order);

            //Persist Order
            $this->em->persist($order);
            $this->em->flush();
        }

        return [
            'item'   => $item,
            'object' => $order,
        ];
    }
}
