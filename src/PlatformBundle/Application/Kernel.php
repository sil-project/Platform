<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PlatformBundle\Application;

use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends BaseKernel
{
    public function registerBundles()
    {
        $bundles = [
            new \Blast\Bundle\ResourceBundle\BlastResourceBundle(),

            // -------------------------------------------------------------------------------------
            // Symfony bundles
            // -------------------------------------------------------------------------------------

            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            // -------------------------------------------------------------------------------------
            // Doctrine
            // -------------------------------------------------------------------------------------

            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new \Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),

            // -------------------------------------------------------------------------------------
            // FOS
            // -------------------------------------------------------------------------------------

            new \FOS\RestBundle\FOSRestBundle(),
            new \FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new \FOS\ElasticaBundle\FOSElasticaBundle(),

            // -------------------------------------------------------------------------------------
            // Knp
            // -------------------------------------------------------------------------------------

            new \Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new \Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
            new \Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),

            // -------------------------------------------------------------------------------------
            // Misc
            // -------------------------------------------------------------------------------------

            new \Bazinga\Bundle\JsTranslationBundle\BazingaJsTranslationBundle(),
            new \JeroenDesloovere\Bundle\VCardBundle\JeroenDesloovereVCardBundle(),
            new \Liip\ImagineBundle\LiipImagineBundle(),
            new \Sparkling\VATBundle\SparklingVATBundle(),
            new \Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            new \Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new \WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new \Lexik\Bundle\MaintenanceBundle\LexikMaintenanceBundle(),
            new \JMS\SerializerBundle\JMSSerializerBundle(),
            new \Misd\PhoneNumberBundle\MisdPhoneNumberBundle(),
            new \Sylius\Bundle\GridBundle\SyliusGridBundle(),

            // -------------------------------------------------------------------------------------
            // Blast bundles
            // -------------------------------------------------------------------------------------

            //new \Blast\Bundle\CoreBundle\BlastCoreBundle(),
            new \Blast\Bundle\UIBundle\BlastUIBundle(),
            new \Blast\Bundle\MenuBundle\BlastMenuBundle(),
            new \Blast\Bundle\PatcherBundle\BlastPatcherBundle(),
            // new \Blast\Bundle\SearchBundle\BlastSearchBundle(),
            new \Blast\Bundle\BaseEntitiesBundle\BlastBaseEntitiesBundle(),
            new \Blast\Bundle\UtilsBundle\BlastUtilsBundle(),
            new \Blast\Bundle\DoctrinePgsqlBundle\BlastDoctrinePgsqlBundle(),
            new \Blast\Bundle\DoctrineSessionBundle\BlastDoctrineSessionBundle(),
            new \Blast\Bundle\DecoratorBundle\BlastDecoratorBundle(),
            new \Blast\Bundle\CsvImportBundle\BlastCsvImportBundle(),
            // new \Blast\Bundle\DashboardBundle\BlastDashboardBundle(),
            new \Blast\Bundle\GridBundle\BlastGridBundle(),

            // -------------------------------------------------------------------------------------
            // Librinfo bundles
            // -------------------------------------------------------------------------------------

            // new \Sil\Bundle\CRMBundle\SilCRMBundle(),
            new \Sil\Bundle\UserBundle\SilUserBundle(),
            // new \Sil\Bundle\EmailBundle\SilEmailBundle(),
            // new \Sil\Bundle\EmailCRMBundle\SilEmailCRMBundle(),
            // new \Sil\Bundle\MediaBundle\SilMediaBundle(),
            // new \Sil\Bundle\UomBundle\SilUomBundle(),
            // new \Sil\Bundle\StockBundle\SilStockBundle(),
            // new \Sil\Bundle\ManufacturingBundle\SilManufacturingBundle(),
            new \Sil\Bundle\ContactBundle\SilContactBundle(),
            new \Sil\Bundle\ProductBundle\SilProductBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new \Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new \Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new \Symfony\Bundle\WebServerBundle\WebServerBundle();
            $bundles[] = new \Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new \Blast\Bundle\ProfilerBundle\BlastProfilerBundle();

            if ($this->getEnvironment() === 'test') {
                $bundles[] = new \Blast\Bundle\TestsBundle\BlastTestsBundle();
            }
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
