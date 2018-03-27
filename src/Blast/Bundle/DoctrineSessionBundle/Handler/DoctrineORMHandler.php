<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\DoctrineSessionBundle\Handler;

use Blast\Bundle\DoctrineSessionBundle\Entity\Session;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class DoctrineORMHandler.
 */
class DoctrineORMHandler implements \SessionHandlerInterface, \SessionUpdateTimestampHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $managerRegistry, $sessionClass)
    {
        $this->entityManager = $managerRegistry->getManagerForClass($sessionClass);
        $this->repository = $this->entityManager->getRepository($sessionClass);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        $qb = $this->repository->createQueryBuilder('s');

        $qb->delete()
            ->where($qb->expr()->eq('s.sessionId', ':sessionId'))
            ->setParameter('sessionId', $sessionId)
            ->getQuery()
            ->execute();

        /* destroy has most of the handler/storage method have to return bool */
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($maxLifetime)
    {
        $qb = $this->repository->createQueryBuilder('s');

        $qb->delete()
            ->where($qb->expr()->lt('s.expiresAt', ':now'))
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->execute();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionId)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        $session = $this->getSession($sessionId);

        if (!$session || is_null($session->getData())) {
            return '';
        }

        $resource = $session->getData();

        return is_resource($resource) ? stream_get_contents($resource) : $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $sessionData)
    {
        $maxLifetime = (int) ini_get('session.gc_maxlifetime');
        $session = $this->getSession($sessionId);
        $expiry = new \DateTime();

        $expiry->add(new \DateInterval('PT' . $maxLifetime . 'S'));

        $session->setData($sessionData);
        $session->setExpiresAt($expiry);

        $this->entityManager->persist($session);
        $this->entityManager->flush($session);

        return true;
    }

    /**
     * @param $sessionId
     *
     * @return Session
     */
    protected function getNewInstance($sessionId)
    {
        $className = $this->repository->getClassName();
        $session = new $className();

        $session->setSessionId($sessionId);

        return $session;
    }

    /**
     * @param $sessionId
     *
     * @return Session
     */
    protected function getSession($sessionId)
    {
        $session = $this->repository->findOneBy([
            'sessionId' => $sessionId,
        ]);

        if (!$session) {
            $session = $this->getNewInstance($sessionId);
        }

        return $session;
    }

    /* https://symfony.com/blog/new-in-symfony-3-4-session-improvements */
    /* @todo: add test for this ! */
    // Checks if a session identifier already exists or not.
    public function validateId($sessionId)
    {
        /* @todo: factorize with getSession */
        $session = $this->repository->findOneBy([
            'sessionId' => $sessionId,
        ]);

        /* @todo: Do we need to check expireAt ? */
        /* ->where($qb->expr()->lt('s.expiresAt', ':now'))
           ->setParameter('now', new \DateTime()) */

        if (!$session) {
            return false;
        }

        return true;
    }

    // Updates the timestamp of a session when its data didn't change.
    public function updateTimestamp($sessionId, $data)
    {
        return $this->write($sessionId, $data);
    }
}
