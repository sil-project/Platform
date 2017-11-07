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

namespace Sil\Bundle\EmailBundle\Entity\OuterExtension;

use Doctrine\Common\Collections\Collection;

trait HasEmailMessages
{
    /**
     * @var Collection
     */
    private $emailMessages;

    /**
     * Add email message.
     *
     * @param object $emailMessage
     *
     * @return self
     */
    public function addEmailMessage($emailMessage)
    {
        //$emailMessage->addOrganism($this); // TODO: synchronously updating the inverse side
        $this->emailMessages[] = $emailMessage;

        return $this;
    }

    /**
     * Remove email message.
     *
     * @param object $emailMessage
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeEmailMessage($emailMessage)
    {
        //$emailMessage->removeOrganism($this); // TODO: synchronously updating the inverse side
        return $this->emailMessages->removeElement($emailMessage);
    }

    /**
     * Get email messages.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmailMessages()
    {
        return $this->emailMessages;
    }
}
