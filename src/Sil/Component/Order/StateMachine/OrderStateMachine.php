<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Order\StateMachine;

use SM\StateMachine\StateMachine;
use Sil\Component\Order\Model\OrderState;

class OrderStateMachine extends StateMachine
{
    protected $config = [
        'graph'         => 'order_state',
        'property_path' => 'value',
        'states'        => [
            OrderState::DRAFT,
            OrderState::VALIDATED,
            OrderState::CANCELLED,
            OrderState::FULFILLED,
            OrderState::DELETED,
        ],
        'transitions' => [
            'delete' => [
                'from' => [OrderState::DRAFT],
                'to'   => OrderState::DELETED,
            ],
            'validate' => [
                'from' => [OrderState::DRAFT],
                'to'   => OrderState::VALIDATED,
            ],
            'fulfill' => [
                'from' => [OrderState::VALIDATED],
                'to'   => OrderState::FULFILLED,
            ],
            'cancel' => [
                'from' => [OrderState::VALIDATED],
                'to'   => OrderState::CANCELLED,
            ],
        ],
    ];

    public function __construct($object)
    {
        parent::__construct($object, $this->config);
    }
}
