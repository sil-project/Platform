<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Entity;

use Sil\Component\Contact\Model\Country as BaseCountry;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;

/**
 * Country.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class Country extends BaseCountry
{
    use Guidable;
}
