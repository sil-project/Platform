<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\AccountBundle\Entity;

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;
use Sil\Component\Account\Model\Account as BaseAccount;

/**
 * Account.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class Account extends BaseAccount
{
    use Guidable;
}
