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

use Symfony\Component\EventDispatcher\Event;

class HookEvent extends Event
{
    const NAME = 'blast.hook';

    /**
     * @var string
     */
    protected $hookName;

    /**
     * @var array
     */
    protected $hookParameters;

    /**
     * @var string
     */
    protected $renderedOutput = '';

    /**
     * @param string $hookName
     * @param array  $hookParameters
     */
    public function __construct($hookName, $hookParameters)
    {
        $this->hookName = $hookName;
        $this->hookParameters = $hookParameters;
    }

    /**
     * @return string
     */
    public function getHookName()
    {
        return $this->hookName;
    }

    /**
     * @param string hookName
     *
     * @return self
     */
    public function setHookName($hookName)
    {
        $this->hookName = $hookName;

        return $this;
    }

    /**
     * @return array
     */
    public function getHookParameters()
    {
        return $this->hookParameters;
    }

    /**
     * @param array hookParameters
     *
     * @return self
     */
    public function setHookParameters(array $hookParameters)
    {
        $this->hookParameters = $hookParameters;

        return $this;
    }

    public function appendOutput($renderedOutput)
    {
        $this->renderedOutput .= $renderedOutput;
    }

    /**
     * @return string
     */
    public function getRenderedOutput()
    {
        return $this->renderedOutput;
    }

    /**
     * @param string renderedOutput
     *
     * @return self
     */
    public function setRenderedOutput($renderedOutput)
    {
        $this->renderedOutput = $renderedOutput;

        return $this;
    }
}
