<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EmailBundle\Services;

/**
 * AddressManager is used to manage addresses as service (librinfo.email.address_manager).
 */
class AddressManager
{
    /**
     * manageAddresses.
     *
     * @param Email $mail
     *
     * @return array
     */
    public function manageAddresses($mail)
    {
        return $mail->getFieldToAsArray();
    }
}
