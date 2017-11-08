<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\DoctrineSessionBundle\Entity;

/**
 * Session.
 */
class Session implements SessionInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $sessionId;

    /**
     * @var string
     */
    private $data;

    /**
     * @var \DateTime
     */
    private $createdAt = null;

    /**
     * @var \DateTime
     */
    private $expiresAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return object this Entity
     */
    public function setId($id)
    {
        $this->$id = $id;

        return $this;
    }

    /**
     * Set sessionId.
     *
     * @param string $sessionId
     *
     * @return Session
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Get sessionId.
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set data.
     *
     * @param string $data
     *
     * @return Session
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data.
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Timestampable
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Set expiresAt.
     *
     * @param string $expiresAt
     *
     * @return Session
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt.
     *
     * @return string
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }
}
