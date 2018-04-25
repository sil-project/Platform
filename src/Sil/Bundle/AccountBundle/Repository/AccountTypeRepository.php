<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\AccountBundle\Repository;

use Blast\Bundle\ResourceBundle\Doctrine\ORM\Repository\ResourceRepository;
use Sil\Component\Account\Repository\AccountTypeRepositoryInterface;

/**
 * AccountType Entity Repository.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class AccountTypeRepository extends ResourceRepository implements AccountTypeRepositoryInterface
{
}
