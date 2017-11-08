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

namespace Blast\Bundle\BaseEntitiesBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\Logger;
use Psr\Log\LoggerAwareInterface;
use Blast\Bundle\BaseEntitiesBundle\EventListener\Traits\ClassChecker;

class NormalizeListener implements LoggerAwareInterface, EventSubscriber
{
    use ClassChecker, Logger;

    private $actions = [];
    private $object;

    public function setActions($actions)
    {
        $this->actions = $actions;
    }

    public function addActions($actions)
    {
        $this->actions = array_merge_recursive($this->actions, $actions);
    }

    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
        );
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $this->object = $eventArgs->getObject();
        $class = str_replace('Proxies\\__CG__\\', '', get_class($this->object));
        if (!array_key_exists($class, $this->actions)) {
            return;
        }

        $this->logger->debug(
            '[NormalizeListener] Entering NormalizeListener for « prePersist » event'
        );

        $this->normalizeFields();
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $this->object = $eventArgs->getObject();
        $class = str_replace('Proxies\\__CG__\\', '', get_class($this->object));
        if (!array_key_exists($class, $this->actions)) {
            return;
        }

        $this->logger->debug(
            '[NormalizeListener] Entering NormalizeListener for « preUpdate » event'
        );

        $this->normalizeFields();
    }

    private function normalizeFields()
    {
        if (!$this->object) {
            return [];
        }
        $class = str_replace('Proxies\\__CG__\\', '', get_class($this->object));

        foreach ($this->actions[$class] as $field => $actions) {
            $this->normalizeField($field, $actions);
        }
    }

    /**
     * @param string $field
     * @param array  $actions (uppercase, titlecase...)
     *
     * @return string|null
     */
    private function normalizeField($field, $actions)
    {
        $getter = 'get' . ucfirst($field);
        if (!method_exists($this->object, $getter)) {
            $this->logger->warn(
                "[NormalizeListener] Method « $getter » does not exist for Entity",
                ['class' => get_class($this->object)]
            );

            return;
        }

        $data = $this->object->$getter();
        $normalized = false;
        foreach ($actions as $action) {
            switch ($action) {
                case 'uppercase':
                case 'upper':
                    $data = mb_strtoupper($data);
                    $normalized = true;
                    break;
                case 'titlecase':
                case 'title':
                    $data = mb_convert_case($data, MB_CASE_TITLE);
                    $normalized = true;
                    break;
                default:
                    $this->logger->warn(
                        "[NormalizeListener] Action « $action » is not in defined normalizers list",
                        ['class' => get_class($this->object)]
                    );
            }
        }
        if (!$normalized) {
            return;
        }

        $setter = 'set' . ucfirst($field);
        if (!method_exists($this->object, $setter)) {
            $this->logger->warn(
                "[NormalizeListener] Method « $setter » does not exist for Entity",
                ['class' => get_class($this->object)]
            );

            return;
        }

        $this->object->$setter($data);
        $this->logger->debug(
            "[NormalizeListener] Normalized field « $field » for Entity",
            ['class' => get_class($this->object), 'field' => $field, 'actions' => implode(', ', $actions)]
        );

        return $data;
    }
}
