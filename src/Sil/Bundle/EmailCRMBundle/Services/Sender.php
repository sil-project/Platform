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

namespace Librinfo\EmailCRMBundle\Services;

use Librinfo\EmailBundle\Services\Sender as BaseSender;
use Librinfo\EmailCRMBundle\Services\SwiftMailer\DecoratorPlugin\Replacements;

class Sender extends BaseSender
{
    /**
     * Sends an email.
     *
     * @param Email $email the email to send
     *
     * @return int number of successfully sent emails
     */
    public function send($email)
    {
        $this->email = $email;
        $this->attachments = $email->getAttachments();

        $addresses = $this->addressManager->manageAddresses($this->email);

        $this->needsSpool = (count($addresses) > 1);

        if ($this->email->getIsTest()) {
            return $this->directSend($this->email->getTestAddressAsArray());
        }

        if ($this->needsSpool) {
            return $this->spoolSend($addresses);
        }

        return $this->directSend($addresses);
    }

    protected function directSend($to, &$failedRecipients = null, $message = null)
    {
        $message = $this->setupSwiftMessage($to, $message);
        $replacements = new Replacements($this->manager);
        $decorator = new \Swift_Plugins_DecoratorPlugin($replacements);

        $this->directMailer->registerPlugin($decorator);
        $sent = $this->directMailer->send($message, $failedRecipients);
        $this->updateEmailEntity($message);

        return $sent;
    }
}
