# Sonata Sylius User Bundle

[![Travis](https://img.shields.io/travis/libre-informatique/SonataSyliusUserBundle.svg?style=flat-square)][travis]
[![Coveralls](https://img.shields.io/coveralls/libre-informatique/SonataSyliusUserBundle.svg?style=flat-square)][coveralls]
[![License](https://img.shields.io/github/license/libre-informatique/SonataSyliusUserBundle.svg?style=flat-square)][license]

[![Latest Stable Version](https://poser.pugx.org/libre-informatique/sonata-sylius-user-bundle/v/stable)](https://packagist.org/packages/libre-informatique/sonata-sylius-user-bundle)
[![Latest Unstable Version](https://poser.pugx.org/libre-informatique/sonata-sylius-user-bundle/v/unstable)](https://packagist.org/packages/libre-informatique/sonata-sylius-user-bundle)
[![Total Downloads](https://poser.pugx.org/libre-informatique/sonata-sylius-user-bundle/downloads)](https://packagist.org/packages/libre-informatique/sonata-sylius-user-bundle)

<!-- TOC depthFrom:1 depthTo:6 withLinks:1 updateOnSave:0 orderedList:0 -->

- [Sonata Sylius User Bundle](#sonata-sylius-user-bundle)
	- [Installation](#installation)
		- [Adding required bundles to the kernel](#adding-required-bundles-to-the-kernel)
		- [Configure Doctrine extensions](#configure-doctrine-extensions)
		- [Update database schema](#update-database-schema)
		- [Configure routes and security](#configure-routes-and-security)
		- [Configure Sylius User Bundle](#configure-sylius-user-bundle)

<!-- /TOC -->


This is a Symfony bundle providing a bridge between [SonataAdmin](https://github.com/sonata-project/SonataAdminBundle)
 and [SyliusUser](http://docs.sylius.org/en/latest/bundles/SyliusUserBundle/index.html)
 (an alternative to [SonataUserBundle](https://github.com/sonata-project/SonataUserBundle)).

The idea behind this bundle was to have user management in Sonata Admin without using
[FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle)
(which was not stable enough by the time we started this project).

[Sylius](http://docs.sylius.org/en/latest/) already had a good user management component and bundle, we just filled the gap...

## Installation

We assume you're familiar with [Composer](http://packagist.org), a dependency manager for PHP.
Use the following command to add the bundle to your `composer.json` and download the package.

If you have [Composer installed globally](http://getcomposer.org/doc/00-intro.md#globally)

```bash
$ composer require libre-informatique/sonata-sylius-user-bundle
```
Otherwise you have to download .phar file.

```bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar require libre-informatique/sonata-sylius-user-bundle
```

### Adding required bundles to the kernel

You need to enable the bundle inside the kernel.

If you're not using any other Sylius bundles, you will also need to add `SyliusUserBundle` and its dependencies to kernel.
Don't worry, everything was automatically installed via Composer.

```php
<?php

// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // SYLIUS USER BUNDLE AND DEPENDENCIES
        new FOS\RestBundle\FOSRestBundle(),
        new JMS\SerializerBundle\JMSSerializerBundle($this),
        new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
        new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
        new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),
        new winzou\Bundle\StateMachineBundle\winzouStateMachineBundle(),
        // Sylius Bundles have to be declared before DoctrineBundle
        new Sylius\Bundle\ResourceBundle\SyliusResourceBundle(),
        new Sylius\Bundle\MailerBundle\SyliusMailerBundle(),
        new Sylius\Bundle\UserBundle\SyliusUserBundle(),

        // OTHER BUNDLES...
        new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
        // [...]

        // SONATA SYLIUS USER BUNDLE
        new Sil\Bundle\SonataSyliusUserBundle\SonataSyliusUserBundle(),
    );
}
```

Note:
Please register `SyliusUserBundle` before `DoctrineBundle`. This is important as it uses listeners which have to be processed first.

### Configure Doctrine extensions

Configure doctrine extensions which are used by the bundle.

```yaml
# app/config/config.yml
stof_doctrine_extensions:
    orm:
        default:
            timestampable: true
```

### Update database schema

Run the following command.

```bash
$ php bin/console doctrine:schema:update --force
```

Warning:
This should be done only in **dev** environment! We recommend using Doctrine migrations, to safely update your schema.

Congratulations! The bundle is now installed and ready to be configured. :boom:

### Configure routes and security

In this chapter, we assume your Sonata Admin routes are prefixed with `/admin`.

Import SonataSyliusUserBundle security routes (for login, lougout an login_check):

```yaml
# app/config/routing.yml

# Security routing for SyliusUserBundle
# (defines login, logout and login_check routes)
sonata_sylius_user_security:
    resource: "@SonataSyliusUserBundle/Resources/config/routing/security.yml"
    prefix: /admin
```

Configure your application security (this is an example):

```yaml
# app/config/security.yml

security:

    encoders:
        Sylius\Component\User\Model\UserInterface: sha512

    providers:
        sonata_user_provider:
            id: sylius.sonata_user_provider.email_or_name_based

    firewalls:
        # disables authentication for assets and the profiler, adapt it according to your needs
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false

        sonata:
            switch_user: true
            context: sonata
            pattern: /admin(?:/.*)?$
            form_login:
                provider: sonata_user_provider
                login_path: sonata_sylius_user_login
                check_path: sonata_sylius_user_login_check
                failure_path: sonata_sylius_user_login
                default_target_path: sonata_admin_dashboard
                use_forward: false
                use_referer: true
            logout:
                path: sonata_sylius_user_logout
                target: sonata_sylius_user_login
            anonymous: true

    access_control:
        - { path: ^/(css|images|js), role: IS_AUTHENTICATED_ANONYMOUSLY } # allow assets for anonymous users
        - { path: ^/admin/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY } # allow resetting password for anonymous users
        - { path: ^/admin/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/login-check$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: "^/admin.*", role: ROLE_ADMINISTRATION_ACCESS }
```

### Configure Sylius User Bundle

SonataSyliusUserBundle provides a configuration file that you can import in your application configuration :

```yaml
# app/config/config.yml

imports:
    - { resource: "@SonataSyliusUserBundle/Resources/config/app/config.yml" }
```

If you want to use your own configuration for SyliusUserBundle (classes, repositoties, templates, etc), then you will have to adapt this config.yml to your needs instead of importing it.


[travis]: https://travis-ci.org/libre-informatique/SonataSyliusUserBundle
[coveralls]: https://coveralls.io/github/libre-informatique/SonataSyliusUserBundle?branch=master
[license]: ./LICENCE.md
