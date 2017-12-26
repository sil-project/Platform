<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use PlatformBundle\Application\Kernel as PlatformKernel;

class AppKernel extends PlatformKernel
{
    public function registerBundles()
    {
        $bundles = [
            // -------------------------------------------------------------------------------------
            // Pltaform generic integration bundle
            // -------------------------------------------------------------------------------------

            new PlatformBundle\PlatformBundle(),
        ];

        return array_merge(parent::registerBundles(), $bundles);
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__) . '/var/cache/' . $this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__) . '/var/logs';
    }
}
