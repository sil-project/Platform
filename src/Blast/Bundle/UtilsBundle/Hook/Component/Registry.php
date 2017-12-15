<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UtilsBundle\Hook\Component;

class Registry
{
    /**
     * @var array
     */
    private $hooks;

    public function __construct()
    {
        $this->hooks = [];
    }

    public function registerHook(AbstractHook $hook, $hookName = null, $hookTemplate = null)
    {
        // Update hook attribute « hookName » from service definition (tag)
        if ($hookName !== null) {
            $hook->setHookName($hookName);
        }

        // Update hook attribute « template » from service definition (tag)
        if ($hookTemplate !== null) {
            $hook->setTemplate($hookTemplate);
        }

        if ($hook->getHookName() === AbstractHook::HOOK_NAME_DUMMY) {
            throw new \Exception(sprintf('Your hook « %s » must redefine the attribute « hookName » in order to be attached to a real hook', get_class($hook)));
        }

        $this->hooks[$hook->getHookName()][] = $hook;
    }

    public function getRegistredHooks()
    {
        return $this->hooks;
    }

    /**
     * @param string $hookName
     *
     * @return array
     */
    public function getHooksByHookName($hookName)
    {
        $hooks = [];

        if (array_key_exists($hookName, $this->hooks)) {
            $hooks = $this->hooks[$hookName];
        }

        return $hooks;
    }
}
