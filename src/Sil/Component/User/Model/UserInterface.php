<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\User\Model;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

/* @todo: maybe add : use Symfony\Component\Security\Core\User\AdvancedUserInterface; */

interface UserInterface extends BaseUserInterface
{
    public function getUsername(): string;

    public function getPassword(): string;

    public function isEnabled(): bool;

    public function setEnabled(?bool $enabled): void;
}
