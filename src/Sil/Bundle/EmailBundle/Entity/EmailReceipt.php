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

namespace Sil\Bundle\EmailBundle\Entity;

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;

/**
 * EmailReceipt.
 */
class EmailReceipt
{
    use BaseEntity;

    /**
     * @var string
     */
    private $address;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var \Sil\Bundle\EmailBundle\Entity\Email
     */
    private $email;

    /**
     * Set address.
     *
     * @param string $address
     *
     * @return EmailReceipt
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return EmailReceipt
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set email.
     *
     * @param \Sil\Bundle\EmailBundle\Entity\Email $email
     *
     * @return EmailReceipt
     */
    public function setEmail(\Sil\Bundle\EmailBundle\Entity\Email $email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return \Sil\Bundle\EmailBundle\Entity\Email
     */
    public function getEmail()
    {
        return $this->email;
    }
}
