<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\EmailCRMBundle\Entity\OuterExtension;

trait HasEmailRecipients
{
    use \Librinfo\CRMBundle\Entity\OuterExtension\HasOrganisms;
    use \Librinfo\CRMBundle\Entity\OuterExtension\HasPositions;
    use \Librinfo\CRMBundle\Entity\OuterExtension\HasContacts;
}
