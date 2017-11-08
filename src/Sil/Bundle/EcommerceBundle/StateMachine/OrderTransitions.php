<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\StateMachine;

class OrderTransitions
{
    public const GRAPH = 'sylius_order';

    public const TRANSITION_CREATE = 'create';
    public const TRANSITION_CANCEL = 'cancel';
    public const TRANSITION_FULFILL = 'fulfill';
    public const TRANSITION_CONFIRM = 'confirm';
    public const TRANSITION_CREATE_DRAFT = 'create_draft';

    private function __construct()
    {
    }
}
