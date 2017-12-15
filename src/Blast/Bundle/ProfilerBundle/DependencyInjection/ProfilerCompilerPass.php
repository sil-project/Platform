<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\ProfilerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ProfilerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('blast_profiler.profiler.admin_collector')) {
            return;
        }

        $adminCollector = $container->findDefinition('blast_profiler.profiler.admin_collector');

        if ($container->has('blast_utils.hook.registry')) {
            $adminCollector->addMethodCall('setHookRegistry', [new Reference('blast_utils.hook.registry')]);
        }
    }
}
