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

namespace Blast\UtilsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class HookCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('blast_utils.hook.registry')) {
            return;
        }

        $registry = $container->findDefinition('blast_utils.hook.registry');

        $taggedServices = $container->findTaggedServiceIds('blast.hook');

        foreach ($taggedServices as $id => $tags) {
            $hookName = null;
            $hookTemplate = null;
            foreach ($tags as $tag) {
                if (isset($tag['hook'])) {
                    $hookName = $tag['hook'];
                }
                if (isset($tag['template'])) {
                    $hookTemplate = $tag['template'];
                }
            }
            $registry->addMethodCall('registerHook', [new Reference($id), $hookName, $hookTemplate]);
        }
    }
}
