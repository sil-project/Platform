# ProjetLiBio

LiBio is an ERP specialized for seeds producers and craftmen

THIS PROJECT IS STILL UNUSABLE, IT'S YET A WORK IN PROGRESS

## Installation from scratch :

```
$ composer require libre-informatique/core-bundle dev-master
$ composer require libre-informatique/crm-bundle dev-master
$ composer require libre-informatique/decorator-bundle dev-master
$ composer require blast-project/base-entities-bundle dev-master
$ composer require friendsofsymfony/user-bundle "~1.3"
$ composer require sonata-project/user-bundle --no-update
$ composer require sonata-project/easy-extends-bundle
```

Then, configure what is needed :
* [libre-informatique/core-bundle](https://github.com/libre-informatique/SymfonyBlastCoreBundle/blob/master/README.md)
* [libre-informatique/crm-bundle](https://github.com/libre-informatique/SymfonyLibrinfoCRMBundle/blob/master/README.md)
* [libre-informatique/decorator-bundle](https://github.com/libre-informatique/SymfonyLibrinfoDecoratorBundle/blob/master/README.md)
* [friendsofsymfony/user-bundle](http://symfony.com/doc/current/bundles/FOSUserBundle/index.html)
* [sonata-project/user-bundle](https://sonata-project.org/bundles/user/2-2/doc/reference/index.html)
* [sonata-project/easy-extends-bundle](https://sonata-project.org/bundles/easy-extends/master/doc/index.html)

And at the end, do not forget :

```
$ app/console assets:install --symlink
$ app/console cache:clear
$ app/console cache:clear --env=prod
```

### Multiple useful bundles from Sonata that need configuration

#### Activating notifications

```
composer require sonata-project/notification-bundle
composer require videlalvaro/php-amqplib --no-update
composer require liip/monitor-bundle --no-update
composer update
```
```
<?php// app/AppKernel.php
class AppKernel {
    public function registerbundles()
    {
        return array(
            // Application Bundles
            // ...
            new Sonata\NotificationBundle\SonataNotificationBundle(),
            // ...
        );
    }
}
```
```
# app/config/config.yml
doctrine:
    dbal:
        # ...

        types:
            json: Sonata\Doctrine\Types\JsonType

    orm:
        # ...
        entity_managers:
            default:
                    # ...
                mappings:
                    # ...
                    SonataNotificationBundle: ~
                    ApplicationSonataNotificationBundle: ~
```
```
# app/config/config.yml
sonata_notification:
    backend: sonata.notification.backend.runtime
```
```
$ app/console sonata:easy-extends:generate SonataNotificationBundle --dest=src
```
```
<?php
// app/AppKernel.php
class AppKernel {
    public function registerbundles()
    {
        return array(
            // Application Bundles
            // ...
            new Application\Sonata\NotificationBundle\ApplicationSonataNotificationBundle(),
            // ...

        )
    }
}
```

#### Internationalization

```
$ composer require sonata-project/intl-bundle
```
```
<?php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new Sonata\IntlBundle\SonataIntlBundle(),
        // ...
    );
}
```

#### More Form & text features using SonataFormatterBundle

```
$ composer require sonata-project/formatter-bundle
```
```
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
        new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
        new Sonata\FormatterBundle\SonataFormatterBundle(),
        // ...
    );
}
```

### Security using FOSUserBundle & SonataUserBundle

Add the bundles in your ```app/AppKernel.php```:
* new FOS\UserBundle\FOSUserBundle(),
*
* new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),

#### Configuration

Add this in your ```app/config/``` files:

```
# app/config/config.yml
sonata_user:
    security_acl: true
    manager_type: orm # can be orm or mongodb


sonata_block:
    blocks:
        #...
        sonata.user.block.menu:    # used to display the menu in profile pages
        sonata.user.block.account: # used to display menu option (login option)

fos_user:
    db_driver:      orm # can be orm or odm
    firewall_name:  main
    user_class:     Application\Sonata\UserBundle\Entity\User
    group:
        group_class:   Application\Sonata\UserBundle\Entity\Group
        group_manager: sonata.user.orm.group_manager                    # If you're using doctrine orm (use sonata.user.mongodb.user_manager for$
    service:
        user_manager: sonata.user.orm.user_manager                      # If you're using doctrine orm (use sonata.user.mongodb.group_manager fo$

doctrine:
    dbal:
        types:
            json: Sonata\Doctrine\Types\JsonType


```

```
# app/config/security.yml
security:
    # [...]

    encoders:
        FOS\UserBundle\Model\UserInterface: sha512

    acl:
        connection: default

    role_hierarchy:
        ROLE_ADMIN:       [ROLE_USER, ROLE_SONATA_ADMIN]
        ROLE_SUPER_ADMIN: [ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]
        SONATA:
            - ROLE_SONATA_PAGE_ADMIN_PAGE_EDIT  # if you are using acl then this line must be commented

    providers:
        fos_userbundle:
            id: fos_user.user_manager

    firewalls:
        # Disabling the security for the web debug toolbar, the profiler and Assetic.
        dev:
            pattern:  ^/(_(profiler|wdt)|css|images|js)/
            security: false

        # -> custom firewall for the admin area of the URL
        admin:
            pattern:            /admin(.*)
            context:            user
            form_login:
                provider:       fos_userbundle
                login_path:     /admin/login
                use_forward:    false
                check_path:     /admin/login_check
                failure_path:   null
            logout:
                path:           /admin/logout
            anonymous:          true

        # -> end custom configuration

        # default login area for standard users

        # This firewall is used to handle the public login area
        # This part is handled by the FOS User Bundle
        main:
            pattern:             .*
            context:             user
            form_login:
                provider:       fos_userbundle
                login_path:     /login
                use_forward:    false
                check_path:     /login_check
                failure_path:   null
            logout:             true
            anonymous:          true

      access_control:
        # URL of FOSUserBundle which need to be available to anonymous users
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }

        # Admin login page needs to be access without credential
        - { path: ^/admin/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/logout$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/login_check$, role: IS_AUTHENTICATED_ANONYMOUSLY }

        # Secured part of the site
        # This config requires being logged for the whole site and having the admin role for the admin part.
        # Change these rules to adapt them to your needs
        - { path: ^/admin/, role: [ROLE_ADMIN, ROLE_SONATA_ADMIN] }
        - { path: ^/.*, role: IS_AUTHENTICATED_ANONYMOUSLY }
```

```
# app/config/routing.yml
_libio_user:
    resource: routing/sonata-user-bundle.yml
```

```
# app/config/routing/sonata-user-bundle.yml
sonata_user:
    resource: '@SonataUserBundle/Resources/config/routing/admin_security.xml'
    prefix: /admin

sonata_user_security:
    resource: "@SonataUserBundle/Resources/config/routing/sonata_security_1.xml"

sonata_user_resetting:
    resource: "@SonataUserBundle/Resources/config/routing/sonata_resetting_1.xml"
    prefix: /resetting

sonata_user_profile:
    resource: "@SonataUserBundle/Resources/config/routing/sonata_profile_1.xml"
    prefix: /profile

sonata_user_register:
    resource: "@SonataUserBundle/Resources/config/routing/sonata_registration_1.xml"
    prefix: /register

sonata_user_change_password:
    resource: "@SonataUserBundle/Resources/config/routing/sonata_change_password_1.xml"
    prefix: /profile
```

#### Final deployment

Extends the SonataUserBundle to be able to work on it :

```
$ app/console sonata:easy-extends:generate SonataUserBundle -d src
```

Then add this new bundle in your ```AppKernel.php``` :
* new Application\Sonata\UserBundle\ApplicationSonataUserBundle(),

```
$ app/console cache:clear
$ app/console cache:clear --env=prod
```

### Troubleshooting

#### Translatable labels are not translated

If you see most labels like ```breadcrumb.link_dashboard``` instead of ```Dashboard``` you may missed this configuration in ```config.yml```

```
# app/config/config.yml
framework:
    # ...
    default_locale:  "%locale%"
    translator: { fallback: "%locale%" }
```

Don't forget to define and set ```%locale%``` parameter in ```parameters.yml``` or directly in ```config.yml``` under ```parameter:``` key

```
# app/config/config.yml
parameters:
    locale: fr
```
or

```
# app/config/paramters.yml
parameters:
    # ...
    locale: fr
    # ...
```

## Tree of dependencies within the application

Strong dependencies:
```
CoreBundle
	BaseEntitiesBundle
		CRMBundle
		SeedsBundle
	DecoratorBundle
		UserBundle
SecurityAccessBundle
UIBundle
```

Soft dependencies:
```
UIBundle
    DecoratorBundle
SecurityAccessBundle
    CRMBundle
    SeedsBundle
```

## Acknowledgments

Thanks to the [Sonata Project](https://sonata-project.org/) and its contributors of course for the greatful libs, but also for many of the text here in this file... Lots of its content is a simple proof-read copy-paste from the [Sonata Project](https://sonata-project.org/).
