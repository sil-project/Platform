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

use Blast\Component\UI\Model\Breadcrumb;
use Blast\Component\UI\Model\BreadcrumbItem;

class BreadcrumbBuilder
{
    /**
     * @var Breadcrumb
     */
    protected $breadcrumb;

    public function __construct()
    {
        $this->createBreadcrumb();
    }

    public function createBreadcrumb(): Breadcrumb
    {
        $this->breadcrumb = new Breadcrumb();

        return $this->breadcrumb;
    }

    public function addItem(string $label, ?string $url = '#'): self
    {
        new BreadcrumbItem($this->breadcrumb, $label, $url);

        return $this;
    }

    /**
     * @return Breadcrumb
     */
    public function getBreadcrumb(): Breadcrumb
    {
        return $this->breadcrumb;
    }
}
