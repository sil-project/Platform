<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UtilsBundle\Twig\Extensions;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Blast\Bundle\UtilsBundle\Hook\Component\Event\HookEvent;

class Hook extends \Twig_Extension
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'blast_hook',
                [$this, 'displayHook'],
                [
                    'is_safe'           => ['html'],
                    'needs_environment' => true,
                ]
            ),
        ];
    }

    public function displayHook(\Twig_Environment $env, $hookName, $hookParameters = null)
    {
        $hookEvent = new HookEvent($hookName, $hookParameters);
        $this->eventDispatcher->dispatch(HookEvent::NAME, $hookEvent);

        return $hookEvent->getRenderedOutput();
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }
}
