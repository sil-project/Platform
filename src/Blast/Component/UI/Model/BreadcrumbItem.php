<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UI\Model;

class BreadcrumbItem
{
    /**
     * Item label.
     *
     * @var string
     */
    protected $label;

    /**
     * item url.
     *
     * @var string
     */
    protected $url;

    /**
     * Item represents current page.
     *
     * @var bool
     */
    protected $current = false;

    /**
     * The breadcrumb.
     *
     * @var Breadcrumb
     */
    protected $breadcrumb;

    public function __construct(Breadcrumb $breadcrumb, $label, $url = '#')
    {
        $this->label = $label;
        $this->url = $url;

        $breadcrumb->addItem($this);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return bool
     */
    public function isCurrent(): bool
    {
        return $this->current;
    }

    /**
     * @param bool $current
     */
    public function setCurrent(bool $current = true): void
    {
        $this->current = $current;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return Breadcrumb
     */
    public function getBreadcrumb(): Breadcrumb
    {
        return $this->breadcrumb;
    }
}
