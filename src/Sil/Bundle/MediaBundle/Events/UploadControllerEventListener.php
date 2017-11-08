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

namespace Sil\Bundle\MediaBundle\Events;

use Symfony\Component\EventDispatcher\GenericEvent;
use Doctrine\ORM\EntityManager;

class UploadControllerEventListener
{
    const PRE_GET_ENTITY = 'sil.events.media.load.preGetEntity';
    const POST_GET_ENTITY = 'sil.events.media.load.postGetEntity';
    const REMOVE_ENTITY = 'sil.events.media.removeEntity';

    /**
     * @var EntityManager
     */
    protected $em;

    public function preGetEntity(GenericEvent $event)
    {
        $repo = $this->em->getRepository('SilMediaBundle:File');

        $file = $repo->find($event->getSubject()['context']['id']);
        if ($file !== null) {
            $event->setArgument('file', $file);
        }
    }

    public function postGetEntity(GenericEvent $event)
    {
    }

    public function setEm(EntityManager $em)
    {
        $this->em = $em;

        return $this;
    }
}
