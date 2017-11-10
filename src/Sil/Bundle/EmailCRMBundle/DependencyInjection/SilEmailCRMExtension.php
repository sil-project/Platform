<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EmailCRMBundle\DependencyInjection;

use Blast\Bundle\CoreBundle\DependencyInjection\BlastCoreExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class SilEmailCRMExtension extends BlastCoreExtension
{
    public function loadSecurity(ContainerBuilder $container)
    {
        if (class_exists('\Sil\Bundle\SecurityBundle\Configurator\SecurityConfigurator')) {
            \Sil\Bundle\SecurityBundle\Configurator\SecurityConfigurator::getInstance($container)->loadSecurityYml(__DIR__ . '/../Resources/config/security.yml');
        }
    }
}
