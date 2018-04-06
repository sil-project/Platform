<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\UserBundle\Entity;

use Blast\Component\Resource\Model\ResourceInterface;
use Sil\Component\User\Model\UserInterface as SilUserInterface;

interface UserInterface extends ResourceInterface, SilUserInterface
{
}
