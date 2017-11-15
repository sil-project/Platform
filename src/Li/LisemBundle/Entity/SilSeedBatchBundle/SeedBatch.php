<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Entity\SilSeedBatchBundle;

use Sil\Bundle\EcommerceBundle\Entity\Association\HasProductVariantsTrait;
use Sil\Bundle\SeedBatchBundle\Entity\SeedBatch as BaseSeedBatch;

class SeedBatch extends BaseSeedBatch
{
    use HasProductVariantsTrait;
}
