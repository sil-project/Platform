<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UIBundle\Twig\Extensions;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class PathBuilderExtension extends \Twig_Extension
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'blastui_build_path',
                [$this, 'buildPath']
            ),
        ];
    }

    /**
     * Builds an url for an object.
     *
     * @param string $routeName
     * @param array  $routeParams
     * @param mixed  $object
     * @param string $objectRegexpPlaceholder
     *
     * @return string
     */
    public function buildPath(string $routeName, array $routeParams = [], $object = null, $objectRegexpPlaceholder = '^resource'): string
    {
        $params = [];
        foreach ($routeParams as $paramName => $routeParam) {
            $placeholderRegexp = sprintf('/%s/', $objectRegexpPlaceholder);
            $placeholderReplacementRegexp = sprintf('/%s\./', $objectRegexpPlaceholder);

            if (preg_match($placeholderRegexp, $routeParam) !== -1) {
                $propertyAccessor = new PropertyAccessor();
                $params[$paramName] = $propertyAccessor->getValue($object, preg_replace($placeholderReplacementRegexp, '', $routeParam));
            } else {
                $params[$paramName] = $routeParam;
            }
        }

        return $this->router->generate($routeName, $params);
    }
}
