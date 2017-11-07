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

namespace Blast\DoctrineSessionBundle\Entity;

interface SessionInterface
{
    /**
     * Set sessionId.
     *
     * @param string $sessionId
     *
     * @return Session
     */
    public function setSessionId($sessionId);

    /**
     * Get sessionId.
     *
     * @return string
     */
    public function getSessionId();

    /**
     * Set data.
     *
     * @param string $data
     *
     * @return Session
     */
    public function setData($data);

    /**
     * Get data.
     *
     * @return string
     */
    public function getData();

    /**
     * Set expiresAt.
     *
     * @param string $expiresAt
     *
     * @return Session
     */
    public function setExpiresAt($expiresAt);

    /**
     * Get expiresAt.
     *
     * @return string
     */
    public function getExpiresAt();
}
