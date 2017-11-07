# SymfonySilVarietyBundle

[![Build Status](https://travis-ci.org/libre-informatique/VarietyBundle.svg?branch=master)](https://travis-ci.org/libre-informatique/VarietyBundle)
[![Coverage Status](https://coveralls.io/repos/github/libre-informatique/VarietyBundle/badge.svg?branch=master)](https://coveralls.io/github/libre-informatique/VarietyBundle?branch=master)
[![License](https://img.shields.io/github/license/libre-informatique/VarietyBundle.svg?style=flat-square)](./LICENCE.md)

[![Latest Stable Version](https://poser.pugx.org/libre-informatique/varieties-bundle/v/stable)](https://packagist.org/packages/libre-informatique/varieties-bundle)
[![Latest Unstable Version](https://poser.pugx.org/libre-informatique/varieties-bundle/v/unstable)](https://packagist.org/packages/libre-informatique/varieties-bundle)
[![Total Downloads](https://poser.pugx.org/libre-informatique/varieties-bundle/downloads)](https://packagist.org/packages/libre-informatique/varieties-bundle)

# Installation
From a basic symfony project

```
composer require sonata-project/admin-bundle 3.x-dev
composer require sonata-project/doctrine-orm-admin-bundle 3.x-dev
composer require blast-project/core-bundle dev-master
composer require blast-project/outer-extension-bundle
composer require libre-informatique/sonata-sylius-user-bundle
composer require libre-informatique/varieties-bundle
composer require sonata-project/intl-bundle
```
# Configuration
add [variety.yml](https://github.com/libre-informatique/LISemSymfonyProject/blob/master/app/config/varieties.yml) in app\config

app\config\config.yml

```yaml
imports:
    - { resource: parameters.yml }
    - { resource: security.yml }
    - { resource: services.yml }
    - { resource: varieties.yml }
# Doctrine Configuration
doctrine:
    dbal:
        driver: pdo_pgsql
        host: '%database_host%'
        port: '%database_port%'
        dbname: '%database_name%'
        user: '%database_user%'
        password: '%database_password%'
        charset: UTF8
    orm:
        auto_generate_proxy_classes: '%kernel.debug%'
        naming_strategy: doctrine.orm.naming_strategy.underscore
        auto_mapping: true
        mappings:
            gedmo_loggable:
                type: annotation
                prefix: Gedmo\Loggable\Entity
                dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Loggable/Entity"
                alias: GedmoLoggable # (optional) it will default to the name set for the mappingmapping
                is_bundle: false

    # Sonata
sonata_block:
  default_contexts: [cms]
  blocks:

    # enable the SonataAdminBundle block
    sonata.admin.block.admin_list:
      contexts:   [admin]

blast_base_entities:
    orm:
        default:
            naming: true
            guidable: true
            timestampable: true
            addressable: true
            treeable: false
            nested_treeable: true
            nameable: true
            labelable: true
            emailable: true
            descriptible: true
            searchable: true
            loggable: true
            sortable: true
            normalize: true
            syliusGuidable: true
```
app\AppKernel.php

```php
<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new AppBundle\AppBundle(),
            # Sonata
            new Sonata\CoreBundle\SonataCoreBundle(), 
            new Sonata\BlockBundle\SonataBlockBundle(), 
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),
            # blast
            new Blast\Bundle\CoreBundle\BlastCoreBundle(),
            new Blast\Bundle\OuterExtensionBundle\BlastOuterExtensionBundle(),
            new Blast\Bundle\BaseEntitiesBundle\BlastBaseEntitiesBundle(),
            new Blast\Bundle\UtilsBundle\BlastUtilsBundle(),
            # bundle
            new Sil\Bundle\VarietyBundle\SilVarietyBundle(),
            new Sil\Bundle\MediaBundle\SilMediaBundle(),
            new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            # sylius
            new Sil\Bundle\SonataSyliusUserBundle\SonataSyliusUserBundle(),
        ];
```
app\config\routing.yml
```yaml
app:
    resource: '@AppBundle/Controller/'
    type: annotation
    
admin:
    resource: '@SonataAdminBundle/Resources/config/routing/sonata_admin.xml'
    prefix: /admin

_sonata_admin:
    resource: .
    type: sonata_admin
    prefix: /admin

blast_core:
    resource: "@BlastCoreBundle/Resources/config/routing.yml" 
    prefix:   /admin
librinfo_media:
    resource: "@SilMediaBundle/Resources/config/routing.yml"
```

```bash
bin/console blast:generate:extension-containers -d
```
src\AppBundle\Entity\LibreVarietyBundle\VarietyExtension.php
```php
<?php

namespace AppBundle\Entity\OuterExtension\SilVarietyBundle;

trait VarietyExtension
{
    use \Sil\Bundle\MediaBundle\Entity\OuterExtension\HasImages;
}
```
app\config\services.yml
```yaml
services:
    sylius.canonicalizer:
        class: Sylius\Component\User\Canonicalizer\Canonicalizer
```
generate database tables
```bash
bin/console doctrine:schema:create
```