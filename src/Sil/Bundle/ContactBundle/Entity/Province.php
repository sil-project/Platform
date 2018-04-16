<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Entity;

use Sil\Component\Contact\Model\Province as BaseProvince;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;

/**
 * Province.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class Province extends BaseProvince
{
    use Guidable;
}
