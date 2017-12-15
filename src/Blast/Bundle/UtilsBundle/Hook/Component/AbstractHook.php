<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UtilsBundle\Hook\Component;

use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManager;

abstract class AbstractHook
{
    const HOOK_NAME_DUMMY = 'blast.hook.dummy';

    /**
     * @var bool
     */
    protected $enabled = true;

    /**
     * @var string
     */
    protected $hookName = self::HOOK_NAME_DUMMY;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
     */
    protected $templateParameters = [];

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * handleParameters should handle $templateParameters. It will be called when
     * the block template will be rendered. Please override this method to set
     * the view parameters of your block.
     */
    public function handleParameters($hookParameters)
    {
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string template
     *
     * @return self
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return array
     */
    public function getTemplateParameters()
    {
        return $this->templateParameters;
    }

    /**
     * @param array templateParameters
     *
     * @return self
     */
    public function setTemplateParameters(array $templateParameters)
    {
        $this->templateParameters = $templateParameters;

        return $this;
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
     * @param RequestStack requestStack
     *
     * @return self
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;

        return $this;
    }

    /**
     * @param EntityManager em
     *
     * @return self
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return self
     */
    public function enable()
    {
        $this->enabled = true;

        return $this;
    }

    /**
     * @return self
     */
    public function disable()
    {
        $this->enabled = false;

        return $this;
    }
}
