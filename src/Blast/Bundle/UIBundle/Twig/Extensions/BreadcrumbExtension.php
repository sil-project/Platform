<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UIBundle\Twig\Extensions;

use Blast\Component\UI\Model\Breadcrumb;
use Blast\Component\UI\Service\BreadcrumbBuilder;

class BreadcrumbExtension extends \Twig_Extension
{
    /**
     * @var BreadcrumbBuilder
     */
    private $breadcrumbBuilder;

    /**
     * @param BreadcrumbBuilder $breadcrumbBuilder
     */
    public function __construct(BreadcrumbBuilder $breadcrumbBuilder)
    {
        $this->breadcrumbBuilder = $breadcrumbBuilder;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'render_breadcrumb',
                [$this, 'renderBreadcrumb'],
                [
                    'is_safe'           => ['html'],
                    'needs_environment' => true,
                ]
            ),
        ];
    }

    public function renderBreadcrumb(\Twig_Environment $env)
    {
        return $env->render('@BlastUI/Layout/Blocks/breadcrumb.html.twig', ['breadcrumb' => $this->breadcrumbBuilder->getBreadcrumb()]);
    }
}
