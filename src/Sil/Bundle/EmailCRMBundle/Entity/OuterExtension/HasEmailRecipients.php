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

namespace Sil\Bundle\EmailCRMBundle\Entity\OuterExtension;

trait HasEmailRecipients
{
    use \Sil\Bundle\CRMBundle\Entity\OuterExtension\HasOrganisms;
    use \Sil\Bundle\CRMBundle\Entity\OuterExtension\HasPositions;
    use \Sil\Bundle\CRMBundle\Entity\OuterExtension\HasContacts;
}
