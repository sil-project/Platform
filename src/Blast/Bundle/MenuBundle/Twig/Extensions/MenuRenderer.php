<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\MenuBundle\Twig\Extensions;

use Blast\Bundle\MenuBundle\Builder\SimpleMenuBuilder;

class MenuRenderer extends \Twig_Extension
{
    /**
     * @var array
     */
    private $menuParameter;

    /**
     * @var SimpleMenuBuilder
     */
    private $builder;

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'render_blast_menu',
                [$this, 'renderMenu'],
                [
                    'is_safe'           => ['html'],
                    'needs_environment' => true,
                ]
            ),
        ];
    }

    public function renderMenu(\Twig_Environment $env, $template = '@BlastMenu/Menu/menu.html.twig', $viewParameterName = 'menu')
    {
        $menuTree = $this->builder->createGlobalSidebarMenu();

        return $env->render($template, [$viewParameterName => $menuTree]);
    }

    /**
     * @param array $menuParameter
     */
    public function setMenuParameter(array $menuParameter): void
    {
        $this->menuParameter = $menuParameter;
    }

    /**
     * @param SimpleMenuBuilder $builder
     */
    public function setBuilder(SimpleMenuBuilder $builder): void
    {
        $this->builder = $builder;
    }
}
