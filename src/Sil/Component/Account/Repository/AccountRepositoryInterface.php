<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Account\Repository;

use Blast\Component\Resource\Repository\ResourceRepositoryInterface;
use Sil\Component\Contact\Model\ContactInterface;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
interface AccountRepositoryInterface extends ResourceRepositoryInterface
{
    /**
     * Retrieve the Accounts of a Contact.
     *
     * @param ContactInterface $contact
     *
     * @return Account
     */
    public function getAccountsForContact(ContactInterface $contact): Account;
}
