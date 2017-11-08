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

namespace Blast\Bundle\UtilsBundle\Hook\Event;

use Blast\Bundle\UtilsBundle\Hook\Registry;
use Symfony\Bundle\TwigBundle\TwigEngine;

class HookListener
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var TwigEngine
     */
    private $templating;

    public function onBlastHook(HookEvent $event)
    {
        $hooks = $this->registry->getHooksByHookName($event->getHookName());

        foreach ($hooks as $hook) {
            $hook->handleParameters($event->getHookParameters());
            $event->appendOutput(
                $this->templating->render($hook->getTemplate(), $hook->getTemplateParameters())
            );
        }
    }

    /**
     * @param Registry registry
     *
     * @return self
     */
    public function setRegistry(Registry $registry)
    {
        $this->registry = $registry;

        return $this;
    }

    /**
     * @param TwigEngine templating
     *
     * @return self
     */
    public function setTemplating(TwigEngine $templating)
    {
        $this->templating = $templating;

        return $this;
    }
}
