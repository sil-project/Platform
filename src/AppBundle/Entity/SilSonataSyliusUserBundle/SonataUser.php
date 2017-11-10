<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity\SilSonataSyliusUserBundle;

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;
use Sil\Bundle\SonataSyliusUserBundle\Entity\SonataUser as BaseSonataUser;
use Sylius\Component\Core\Model\CustomerInterface;

class SonataUser extends BaseSonataUser
{
    use Guidable;

    /**
     * @var CustomerInterface
     */
    protected $customer;
}
