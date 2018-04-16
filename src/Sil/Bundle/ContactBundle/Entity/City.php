<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Entity;

use Sil\Component\Contact\Model\City as BaseCity;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;

/**
 * City.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class City extends BaseCity
{
    use Guidable;
}
