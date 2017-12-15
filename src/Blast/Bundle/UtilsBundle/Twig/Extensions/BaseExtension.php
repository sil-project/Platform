<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UtilsBundle\Twig\Extensions;

use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class BaseExtension extends \Twig_Extension
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getBlockFromTemplate', [$this, 'getBlockFromTemplate'], ['needs_environment' => true]),
            new \Twig_SimpleFunction('isObjectInstanceOf', [$this, 'isObjectInstanceOf']),
            new \Twig_SimpleFunction('isExtensionLoaded', [$this, 'isExtensionLoaded'], ['needs_environment' => true]),
            new \Twig_SimpleFunction('isFunctionLoaded', [$this, 'isFunctionLoaded'], ['needs_environment' => true]),
            new \Twig_SimpleFunction('routeExists', [$this, 'routeExists']),
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('trimArray', [$this, 'trimArray']),
        ];
    }

    public function getTests()
    {
        return [
            new \Twig_SimpleTest('instanceof', [$this, 'isInstanceof']),
        ];
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function routeExists($name)
    {
        return (null === $this->router->getRouteCollection()->get($name)) ? false : true;
    }

    /**
     * @param Twig_Environment $twig
     * @param string           $name
     *
     * @return bool
     */
    public function isFunctionLoaded(Twig_Environment $twig, $name)
    {
        return $twig->getFunction($name) !== false;
    }

    /**
     * @param Twig_Environment $twig
     * @param string           $name
     *
     * @return bool
     */
    public function isExtensionLoaded(Twig_Environment $twig, $name)
    {
        return $twig->hasExtension($name);
    }

    /**
     * @param object $object
     * @param string $class
     *
     * @return bool
     */
    public function isObjectInstanceOf($object, $class)
    {
        return $object instanceof $class;
    }

    /**
     * Test usage in template : {{ myobject is instanceof(myclass) }}.
     *
     * @param object $object
     * @param string $class
     *
     * @return bool
     */
    public function isInstanceof($object, $class)
    {
        return $object instanceof $class;
    }

    /**
     * @param Twig_Environment $twig
     * @param string           $template
     * @param string           $block
     *
     * @return bool
     */
    public function getBlockFromTemplate($twig, $template, $block, $vars = [])
    {
        return $twig
            ->loadTemplate($template)
            ->renderBlock($block, $vars)
        ;
    }

    /**
     * trimArray trims all array items.
     *
     * @param array $array
     *
     * @return array
     */
    public function trimArray($array)
    {
        return array_map('trim', $array);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'blast_utils_base_extension';
    }

    /**
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }
}
