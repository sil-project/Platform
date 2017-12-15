<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Services;

use Doctrine\ORM\EntityManager;
use Sil\Bundle\EcommerceBundle\Entity\OrderInterface;
use SM\Factory\Factory;
use Sylius\Bundle\OrderBundle\NumberAssigner\OrderNumberAssigner;

class OrderManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Factory
     */
    private $stateMachine;

    /**
     * @var OrderNumberAssigner
     */
    private $orderNumberAssigner;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validateOrder(OrderInterface $object)
    {
        $order = $object;

        if ($order->getNumber() === null) {
            $this->orderNumberAssigner->assignNumber($order);
            $this->em->flush($order);
        }

        $stateMachine = $this->stateMachine->get($order, 'sylius_order');

        if ($stateMachine->can('fulfill') && $order->getItems()->count() > 0) {
            $stateMachine->apply('fulfill');
        }
    }

    /**
     * @param Factory stateMachine
     *
     * @return self
     *
     * @todo: should be named setStateMachineFactory
     */
    public function setStateMachine(Factory $stateMachine)
    {
        $this->stateMachine = $stateMachine;

        return $this;
    }

    /**
     * @param OrderNumberAssigner orderNumberAssigner
     */
    public function setOrderNumberAssigner(OrderNumberAssigner $orderNumberAssigner): void
    {
        /*@todo : maybe should used Number generator as in OrderCreationManager */

        $this->orderNumberAssigner = $orderNumberAssigner;
    }
}
