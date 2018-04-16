<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Entity;

use Sil\Component\Contact\Model\Address as BaseAddress;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;

/**
 * Address.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class Address extends BaseAddress
{
    use Guidable;

    const TYPE_HOME = 'type_home';
    const TYPE_WORK = 'type_work';
    const TYPE_OTHER = 'type_other';

    public static function getTypes()
    {
        $prefix = 'sil_contact.address.choices.';

        return [
            $prefix . 'type_home'  => self::TYPE_HOME,
            $prefix . 'type_work'  => self ::TYPE_WORK,
            $prefix . 'type_other' => self::TYPE_OTHER,
        ];
    }
}
