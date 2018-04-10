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

namespace Blast\Component\Resource;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class Actions
{
    public const SHOW = 'show';
    public const LIST = 'list';
    public const CREATE = 'create';
    public const UPDATE = 'update';
    public const DELETE = 'delete';

    protected static $options = [
      'list'   => ['methods' =>['GET']],
      'show'   => ['methods' =>['GET'], 'identifier' => 'id'],
      'create' => ['methods' =>['GET', 'POST']],
      'update' => ['methods' =>['GET', 'POST'], 'identifier' => 'id'],
      'delete' => ['methods' =>['GET'], 'identifier' => 'id'],
    ];

    public static function getActions(): array
    {
        return (new \ReflectionClass(__CLASS__))->getConstants();
    }

    public static function containsOnlyActions(array $actions): bool
    {
        $defaultActions = self::getActions();
        foreach ($actions as $action) {
            if (!in_array($action, $defaultActions)) {
                return false;
            }
        }

        return true;
    }

    public static function methodsForAction(string $action): array
    {
        if (!isset(self::$options[$action])) {
            return [];
        }

        return self::$options[$action]['methods'];
    }

    public static function identifierForAction(string $action): ?string
    {
        if (!isset(self::$options[$action])) {
            return null;
        }

        return self::$options[$action]['identifier'] ?? null;
    }
}
