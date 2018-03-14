<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UI\Service;

use Blast\Component\UI\Model\BreadcrumbItem;
use Blast\Component\UI\Service\BreadcrumbBuilder as BaseBreadcrumbBuilder;
use Symfony\Component\Routing\RouterInterface;

class BreadcrumbBuilder extends BaseBreadcrumbBuilder
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        parent::__construct();
        $this->router = $router;
    }

    public function addItemWithRoute(string $label, string $routeName, array $routeParams = []): self
    {
        $url = $this->router->generate($routeName, $routeParams);

        new BreadcrumbItem($this->breadcrumb, $label, $url);

        return $this;
    }
}
