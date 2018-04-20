<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\GridBundle\Entity;

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;
use Blast\Component\Grid\Model\CustomReport as BaseCustomReport;

class CustomReport extends BaseCustomReport
{
    use Guidable;
}
