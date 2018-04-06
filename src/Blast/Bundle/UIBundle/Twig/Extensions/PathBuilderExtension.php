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

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'blastui_build_path',
                [$this, 'buildPath']
            ),
        ];
    }

    public function buildPath(string $routeName, array $routeParams = [], $object = null, $objectStringPlaceholder = '%item%'): string
    {
        $params = [];
        foreach ($routeParams as $paramName => $routeParam) {
            if (strpos($objectStringPlaceholder, $routeParam) !== -1) {
                $propertyAccessor = new PropertyAccessor();
                $params[$paramName] = $propertyAccessor->getValue($object, str_replace($objectStringPlaceholder . '.', '', $routeParam));
            } else {
                $params[$paramName] = $routeParam;
            }
        }

        return $this->router->generate($routeName, $params);
    }
}
