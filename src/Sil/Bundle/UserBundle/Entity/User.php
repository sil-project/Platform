<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\UserBundle\Entity;

use Sil\Component\User\Model\User as SilUser;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;

class User extends SilUser implements UserInterface, \Serializable, EncoderAwareInterface
{
    /* hum not so well named trait which had the id for this object */
    use Guidable;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getUsername();
    }

    /**
     * @todo: move this in a Expirable Trait (or not)
     */
    public function isExpired(): bool
    {
        return (bool) ($this->expiresAt !== null ? $this->expiresAt < new \DateTime() : false);
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->email,
            $this->enabled,
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->email,
            $this->enabled
        ) = unserialize($serialized);
    }

    /**
     * Returns the encoder name to be used in app/config/security.yml under key `security.encoders`.
     */
    public function getEncoderName(): string
    {
        return 'default';
    }
}
