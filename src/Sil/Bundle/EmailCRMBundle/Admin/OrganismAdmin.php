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

namespace Librinfo\EmailCRMBundle\Admin;

use Librinfo\CRMBundle\Admin\OrganismAdmin as BaseOrganismAdmin;

class OrganismAdmin extends BaseOrganismAdmin
{
    protected $baseRouteName = 'admin_librinfo_emailcrm_organism';
    protected $baseRoutePattern = 'librinfo/emailcrm/organism';
}
