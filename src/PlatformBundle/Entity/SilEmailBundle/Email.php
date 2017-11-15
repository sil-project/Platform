<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PlatformBundle\Entity\SilEmailBundle;

use Sil\Bundle\CRMBundle\Entity\Association\HasOrganismsTrait;
use Sil\Bundle\CRMBundle\Entity\Association\HasPositionsTrait;
use Sil\Bundle\CRMBundle\Entity\Association\HasCirclesTrait;
use Sil\Bundle\EmailBundle\Entity\Email as BaseEmail;

class Email extends BaseEmail
{
    use HasOrganismsTrait,
        HasPositionsTrait,
        HasCirclesTrait;
}
