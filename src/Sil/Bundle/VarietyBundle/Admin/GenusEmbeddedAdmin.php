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

namespace Sil\Bundle\VarietyBundle\Admin;

use Blast\Bundle\CoreBundle\Admin\CoreAdmin;
use Blast\Bundle\CoreBundle\Admin\Traits\EmbeddedAdmin;

class GenusEmbeddedAdmin extends CoreAdmin
{
    use EmbeddedAdmin;

    // protected $baseRouteName = 'admin_vendor_bundlename_adminclassname';
    // protected $baseRoutePattern = 'unique-route-pattern';
}
