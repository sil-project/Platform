<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ManufacturingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Blast\Bundle\ResourceBundle\DependencyInjection\Configuration\ResourceConfigurationTrait;
use Sil\Bundle\ManufacturingBundle\Domain\Entity;

class Configuration implements ConfigurationInterface
{
    use ResourceConfigurationTrait;

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sil_manufacturing');

        $this->addResourcesSection($rootNode);

        return $treeBuilder;
    }

    private function addResourceDefinitions(NodeBuilder $resourceNode)
    {
        $this->addResourceDefinition($resourceNode, 'bom', Entity\Bom::class);
        $this->addResourceDefinition($resourceNode, 'bom_line', Entity\BomLine::class);
    }
}
