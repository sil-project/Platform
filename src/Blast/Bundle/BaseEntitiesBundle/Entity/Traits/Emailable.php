<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Entity\Traits;

trait Emailable
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @var bool
     */
    protected $emailNpai = false;

    /**
     * @var bool
     */
    protected $emailNoNewsletter = false;

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return Emailable
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isEmailNpai()
    {
        return $this->emailNpai;
    }

    /**
     * @param bool $emailNpai
     *
     * @return Emailable
     */
    public function setEmailNpai($emailNpai)
    {
        $this->emailNpai = $emailNpai;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmailNoNewsletter()
    {
        return $this->emailNoNewsletter;
    }

    /**
     * @param bool $emailNoNewsletter
     *
     * @return Emailable
     */
    public function setEmailNoNewsletter($emailNoNewsletter)
    {
        $this->emailNoNewsletter = $emailNoNewsletter;

        return $this;
    }
}
