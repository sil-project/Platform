<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class SyliusTaxCalculationStrategiesCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('sylius.taxation.calculation_strategy');

        $listOfCalculationStrategy = [];

        foreach ($taggedServices as $serviceId => $calculationStrategyConfig) {
            $listOfCalculationStrategy[$calculationStrategyConfig[0]['type']] = $calculationStrategyConfig[0]['label'];
        }

        $container->setParameter('sylius.taxation.calculation_strategy.list_values', $listOfCalculationStrategy);
    }
}
