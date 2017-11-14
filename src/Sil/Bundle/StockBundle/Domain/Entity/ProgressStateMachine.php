<?php

declare(strict_types=1);

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Entity;

use SM\StateMachine\StateMachine;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class ProgressStateMachine extends StateMachine
{
    const DRAFT = 'draft';
    const CONFIRMED = 'confirmed';
    const PARTIALLY_AVAILABLE = 'partially_available';
    const AVAILABLE = 'available';
    const DONE = 'done';
    const CANCELED = 'canceled';

    protected $config = [
        'graph'         => 'progress_state',
        'property_path' => 'value',
        'states'        => [
            self::DRAFT,
            self::CONFIRMED,
            self::PARTIALLY_AVAILABLE,
            self::AVAILABLE,
            self::DONE,
            self::CANCELED,
        ],
        'transitions' => [
            'confirm' => [
                'from' => [self::CONFIRMED, self::DRAFT, self::AVAILABLE],
                'to'   => self::CONFIRMED,
            ],
            'back_to_draft' => [
                'from' => [self::DRAFT, self::CONFIRMED],
                'to'   => self::DRAFT,
            ],
            'partially_available' => [
                'from' => [self::PARTIALLY_AVAILABLE, self::CONFIRMED],
                'to'   => self::PARTIALLY_AVAILABLE,
            ],
            'available' => [
                'from' => [self::AVAILABLE, self::CONFIRMED, self::PARTIALLY_AVAILABLE],
                'to'   => self::AVAILABLE,
            ],
            'done' => [
                'from' => [self::DONE, self::AVAILABLE],
                'to'   => self::DONE,
            ],
            'cancel' => [
                'from' => [self::CANCELED, self::CONFIRMED, self::PARTIALLY_AVAILABLE, self::AVAILABLE],
                'to'   => self::CANCELED,
            ],
        ],
    ];

    public function __construct($object)
    {
        parent::__construct($object, $this->config);
    }
}
