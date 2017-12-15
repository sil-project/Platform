<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EmailCRMBundle\Admin;

use Sil\Bundle\CRMBundle\Admin\OrganismAdmin as BaseOrganismAdmin;

class OrganismAdmin extends BaseOrganismAdmin
{
    protected $baseRouteName = 'admin_email_organism';
    protected $baseRoutePattern = 'email/organism';
}
