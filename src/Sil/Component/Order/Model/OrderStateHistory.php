<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Order\Model;

use DateTime;

class OrderStateHistory
{
    /**
     * The order.
     *
     * @var OrderInterface
     */
    protected $order;

    /**
     * The state chenged.
     *
     * @var OrderStateInterface
     */
    protected $state;

    /**
     * The DateTime of state change.
     *
     * @var DateTime
     */
    protected $date;

    public function __construct(OrderInterface $order, OrderStateInterface $state)
    {
        $this->date = new DateTime();
        $this->order = $order;
        $this->state = $state;
    }

    /**
     * @return OrderInterface
     */
    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    /**
     * @return OrderStateInterface
     */
    public function getState(): OrderStateInterface
    {
        return $this->state;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }
}
