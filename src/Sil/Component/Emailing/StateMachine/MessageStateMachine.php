<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Emailing\StateMachine;

use SM\StateMachine\StateMachine;
use Sil\Component\Emailing\Model\MessageState;

class MessageStateMachine extends StateMachine
{
    protected $config = [
        'graph'         => 'message_state',
        'property_path' => 'value',
        'states'        => [
            MessageState::DRAFT,
            MessageState::VALIDATED,
            MessageState::SENT,
            MessageState::DELETED,
        ],
        'transitions' => [
            'delete' => [
                'from' => [MessageState::DRAFT, MessageState::VALIDATED],
                'to'   => MessageState::DELETED,
            ],
            'validate' => [
                'from' => [MessageState::DRAFT],
                'to'   => MessageState::VALIDATED,
            ],
            'sent' => [
                'from' => [MessageState::VALIDATED, MessageState::SENT],
                'to'   => MessageState::SENT,
            ],
        ],
    ];

    public function __construct($object)
    {
        parent::__construct($object, $this->config);
    }
}
