<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EmailCRMBundle\Entity\OuterExtension;

trait HasEmailRecipients
{
    use \Sil\Bundle\CRMBundle\Entity\HasOrganismsTrait;
    use \Sil\Bundle\CRMBundle\Entity\HasPositionsTrait;
    use \Sil\Bundle\CRMBundle\Entity\HasContactsTrait;
}
