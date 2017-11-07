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

namespace Sil\Bundle\EcommerceBundle\DependencyInjection;

use Blast\Bundle\CoreBundle\DependencyInjection\BlastCoreExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SilEcommerceExtension extends BlastCoreExtension
{
    /**
     * {@inheritdoc}
     */
    public function loadCodeGenerators(ContainerBuilder $container, array $config)
    {
        foreach (['product', 'product_variant', 'product_variant_embedded', 'invoice'] as $cg) {
            $container->setParameter("librinfo_ecommerce.code_generator.$cg", $config['code_generator'][$cg]);
        }
        $container->setParameter('librinfo_ecommerce.invoice.template', $config['invoice']['template']);

        return $this;
    }
}
