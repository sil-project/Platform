<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SeedBatchBundle\Admin;

use Blast\Bundle\CoreBundle\Admin\Traits\EmbeddedAdmin;

class SeedBatchEmbeddedAdmin extends SeedBatchAdmin
{
    use EmbeddedAdmin;

    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.seed_batch.seed_farm';

    protected $baseRouteName = 'admin_sil_seed_batch_seed_batch_embedded';
    protected $baseRoutePattern = 'sil/seedbatch/seedbatch_embedded';
}
