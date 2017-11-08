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

namespace Sil\Bundle\SeedBatchBundle\Admin;

use Blast\Bundle\CoreBundle\Admin\Traits\EmbeddedAdmin;

class SeedBatchEmbeddedAdmin extends SeedBatchAdmin
{
    use EmbeddedAdmin;

    protected $baseRouteName = 'admin_sil_seedbatch_seedbatch_embedded';
    protected $baseRoutePattern = 'sil/seedbatch/seedbatch_embedded';
}
