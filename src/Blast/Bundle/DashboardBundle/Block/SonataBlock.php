<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\DashboardBundle\Block;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;

class SonataBlock extends AbstractBlockService
{
    /**
     * @var BlockRegistry
     */
    private $registry;

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'url'      => false,
            'title'    => 'blast.label.dashboard_block_title',
            'template' => '@BlastDashboardBundle/Resources/views/mainDashboard.html.twig',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $dashboardBlocks = $this->registry->getRegistredBlocks();

        return $this->renderResponse($blockContext->getTemplate(), array(
            'block_context'    => $blockContext,
            'block'            => $blockContext->getBlock(),
            'dashboard_blocks' => $dashboardBlocks ?: [],
        ), $response);
    }

    /**
     * @param BlockRegistry registry
     *
     * @return self
     */
    public function setRegistry(BlockRegistry $registry)
    {
        $this->registry = $registry;

        return $this;
    }
}
