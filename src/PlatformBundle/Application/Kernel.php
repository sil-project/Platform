<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
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
            // Sylius bundles
            // -------------------------------------------------------------------------------------

            new \Sylius\Bundle\OrderBundle\SyliusOrderBundle(),
            new \Sylius\Bundle\MoneyBundle\SyliusMoneyBundle(),
            new \Sylius\Bundle\CurrencyBundle\SyliusCurrencyBundle(),
            new \Sylius\Bundle\LocaleBundle\SyliusLocaleBundle(),
            new \Sylius\Bundle\ProductBundle\SyliusProductBundle(),
            new \Sylius\Bundle\ChannelBundle\SyliusChannelBundle(),
            new \Sylius\Bundle\AttributeBundle\SyliusAttributeBundle(),
            new \Sylius\Bundle\TaxationBundle\SyliusTaxationBundle(),
            new \Sylius\Bundle\ShippingBundle\SyliusShippingBundle(),
            new \Sylius\Bundle\PaymentBundle\SyliusPaymentBundle(),
            new \Sylius\Bundle\MailerBundle\SyliusMailerBundle(),
            new \Sylius\Bundle\PromotionBundle\SyliusPromotionBundle(),
            new \Sylius\Bundle\AddressingBundle\SyliusAddressingBundle(),
            new \Sylius\Bundle\InventoryBundle\SyliusInventoryBundle(),
            new \Sylius\Bundle\TaxonomyBundle\SyliusTaxonomyBundle(),
            new \Sylius\Bundle\UserBundle\SyliusUserBundle(),
            new \Sylius\Bundle\CustomerBundle\SyliusCustomerBundle(),
            new \Sylius\Bundle\UiBundle\SyliusUiBundle(),
            new \Sylius\Bundle\ReviewBundle\SyliusReviewBundle(),
            new \Sylius\Bundle\CoreBundle\SyliusCoreBundle(),
            new \Sylius\Bundle\ResourceBundle\SyliusResourceBundle(),
            new \Sylius\Bundle\GridBundle\SyliusGridBundle(),
            new \Sylius\Bundle\FixturesBundle\SyliusFixturesBundle(),
            new \Sylius\Bundle\AdminBundle\SyliusAdminBundle(),
            new \Sylius\Bundle\ShopBundle\SyliusShopBundle(),
            new \Sylius\Bundle\AdminApiBundle\SyliusAdminApiBundle(),

            // -------------------------------------------------------------------------------------
            // Symfony bundles
            // -------------------------------------------------------------------------------------

            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            // -------------------------------------------------------------------------------------
            // Doctrine
            // -------------------------------------------------------------------------------------

            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new \Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),

            // -------------------------------------------------------------------------------------
            // Sonata
            // -------------------------------------------------------------------------------------

            new \Sonata\CoreBundle\SonataCoreBundle(),
            new \Sonata\AdminBundle\SonataAdminBundle(),
            new \Sonata\BlockBundle\SonataBlockBundle(),
            new \Sonata\IntlBundle\SonataIntlBundle(),
            new \Exporter\Bridge\Symfony\Bundle\SonataExporterBundle(),
            new \Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),

            // -------------------------------------------------------------------------------------
            // FOS
            // -------------------------------------------------------------------------------------

            new \FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new \FOS\OAuthServerBundle\FOSOAuthServerBundle(),
            new \FOS\RestBundle\FOSRestBundle(),
            new \FOS\ElasticaBundle\FOSElasticaBundle(),

            // -------------------------------------------------------------------------------------
            // JMS
            // -------------------------------------------------------------------------------------

            new \JMS\SerializerBundle\JMSSerializerBundle(),

            // -------------------------------------------------------------------------------------
            // Knp
            // -------------------------------------------------------------------------------------

            new \Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new \Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new \Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
            new \Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),

            // -------------------------------------------------------------------------------------
            // Misc
            // -------------------------------------------------------------------------------------

            new \Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),
            new \Bazinga\Bundle\JsTranslationBundle\BazingaJsTranslationBundle(),
            new \JeroenDesloovere\Bundle\VCardBundle\JeroenDesloovereVCardBundle(),
            new \Liip\ImagineBundle\LiipImagineBundle(),
            new \Payum\Bundle\PayumBundle\PayumBundle(),
            new \Sparkling\VATBundle\SparklingVATBundle(),
            new \Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            new \Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new \WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new \winzou\Bundle\StateMachineBundle\winzouStateMachineBundle(),
            new \Lexik\Bundle\MaintenanceBundle\LexikMaintenanceBundle(),

            // -------------------------------------------------------------------------------------
            // Sylius main bundles
            // -------------------------------------------------------------------------------------

            new \Sylius\Bundle\PayumBundle\SyliusPayumBundle(), // must be added after PayumBundle.
            new \Sylius\Bundle\ThemeBundle\SyliusThemeBundle(), // must be added after FrameworkBundle

            // -------------------------------------------------------------------------------------
            // Blast bundles
            // -------------------------------------------------------------------------------------

            new \Blast\Bundle\CoreBundle\BlastCoreBundle(),
            new \Blast\Bundle\MenuBundle\BlastMenuBundle(),
            new \Blast\Bundle\PatcherBundle\BlastPatcherBundle(),
            new \Blast\Bundle\SearchBundle\BlastSearchBundle(),
            new \Blast\Bundle\BaseEntitiesBundle\BlastBaseEntitiesBundle(),
            new \Blast\Bundle\UtilsBundle\BlastUtilsBundle(),
            new \Blast\Bundle\DoctrinePgsqlBundle\BlastDoctrinePgsqlBundle(),
            new \Blast\Bundle\DoctrineSessionBundle\BlastDoctrineSessionBundle(),
            new \Blast\Bundle\DecoratorBundle\BlastDecoratorBundle(),
            new \Blast\Bundle\CsvImportBundle\BlastCsvImportBundle(),
            new \Blast\Bundle\DashboardBundle\BlastDashboardBundle(),

            // -------------------------------------------------------------------------------------
            // Librinfo bundles
            // -------------------------------------------------------------------------------------

            new \Sil\Bundle\CRMBundle\SilCRMBundle(),
            new \Sil\Bundle\SonataSyliusUserBundle\SilSonataSyliusUserBundle(),
            new \Sil\Bundle\EmailBundle\SilEmailBundle(),
            new \Sil\Bundle\EmailCRMBundle\SilEmailCRMBundle(),
            new \Sil\Bundle\MediaBundle\SilMediaBundle(),
            new \Sil\Bundle\EcommerceBundle\SilEcommerceBundle(),
            new \Sil\Bundle\SyliusPayboxBundle\SilSyliusPayboxBundle(),
            new \Sil\Bundle\UomBundle\SilUomBundle(),
            new \Sil\Bundle\StockBundle\SilStockBundle(),
            new \Sil\Bundle\ManufacturingBundle\SilManufacturingBundle(),
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
