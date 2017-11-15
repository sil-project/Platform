<?php

/*
 * This file is part of the Lisem Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Mailer\Adapter;

use Sylius\Bundle\MailerBundle\Sender\Adapter\SwiftMailerAdapter;

/**
 * This class extends the default Sylius email sender adapter to allow self-signed certificates.
 *
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class CustomAdapter extends SwiftMailerAdapter
{
    /**
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        parent::__construct($mailer);
        if (method_exists($this->mailer->getTransport(), 'setStreamOptions')) {
            $this->mailer->getTransport()->setStreamOptions(['ssl' => [
            'allow_self_signed' => true,
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            ]]);
        }
    }
}
