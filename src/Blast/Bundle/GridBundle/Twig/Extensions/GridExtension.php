<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\GridBundle\Twig\Extensions;

use Sylius\Component\Grid\View\GridViewInterface;

class GridExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $gridActionsDefinitions;

    public function __construct(array $gridActionsDefinitions)
    {
        $this->gridActionsDefinitions = $gridActionsDefinitions;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'blast_grid_render_item_actions',
                [$this, 'renderGridItemActions'],
                [
                    'is_safe'           => ['html'],
                    'needs_environment' => true,
                ]
            ),
        ];
    }

    /**
     * Renders actions for a grid element item.
     *
     * @param \Twig_Environment $env
     * @param GridViewInterface $grid
     * @param mixed             $data
     *
     * @return string
     */
    public function renderGridItemActions(\Twig_Environment $env, GridViewInterface $grid, $data): string
    {
        $actions = $grid->getDefinition()->getActionGroup('item')->getActions();
        $rendered = '';

        $actionsCanBeGrouped = $this->checkActionsAreOnlyDefault($actions);

        if ($actionsCanBeGrouped) {
            $rendered = $env->render('@BlastUI/Grid/Action/groupedActions.html.twig', [
                'actions' => $actions,
                'data'    => $data,
            ]);
        } else {
            $syliusGridExt = $env->getFunction('sylius_grid_render_action')->getCallable()[0];

            foreach ($actions as $action) {
                $rendered .= $syliusGridExt->renderAction($grid, $action, $data);
            }
        }

        return $rendered;
    }

    /**
     * Check if actions are all of type « default ».
     *
     * @param array $actions
     *
     * @return bool
     */
    private function checkActionsAreOnlyDefault(array $actions): bool
    {
        foreach ($actions as $action) {
            if ($action->getType() !== 'default') {
                return false;
            }
        }

        return true;
    }
}
