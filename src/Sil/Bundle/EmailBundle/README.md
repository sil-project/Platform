# SymfonySilEmailBundle

[![Travis](https://img.shields.io/travis/libre-informatique/EmailBundle.svg?style=flat-square)][travis]
[![Coveralls](https://img.shields.io/coveralls/libre-informatique/EmailBundle.svg?style=flat-square)][coveralls]
[![License](https://img.shields.io/github/license/libre-informatique/EmailBundle.svg?style=flat-square)][license]

[![Latest Stable Version](https://poser.pugx.org/libre-informatique/email-bundle/v/stable)](https://packagist.org/packages/libre-informatique/email-bundle)
[![Latest Unstable Version](https://poser.pugx.org/libre-informatique/email-bundle/v/unstable)](https://packagist.org/packages/libre-informatique/email-bundle)
[![Total Downloads](https://poser.pugx.org/libre-informatique/email-bundle/downloads)](https://packagist.org/packages/libre-informatique/email-bundle)


## About

 The Libre Informatique *EmailBundle* leverages Swiftmailer and the Libre Informatique *CoreBundle* to provide seemless email and newsletter functionalities.
 Features include database spooling, configurable spool flush command, email openings and link clicks tracking along with stats display, inline attachments, templating, duplication, ... 

## Installation

``` $ composer require libre-informatique/email-bundle ```

```php
// app/AppKernel.php
// ...
public function registerBundles()
{
    $bundles = array(
        // ...
            
        // the libre-informatique bundles
        new Sil\Bundle\EmailBundle\SilEmailBundle(),
            
        // your personal bundles
        // ...
    );
}
```

## Configuration

### Dependencies

```php
    // app/AppKernel.php
    // ...
    public function registerBundles()
    {
        $bundles = array(
            // ...
            
            // Sonata
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),

            // Blast
            new Blast\Bundle\OuterExtensionBundle\BlastOuterExtensionBundle(),
            new Blast\Bundle\CoreBundle\BlastCoreBundle(),
            new lbr\BlastTestBundle\BlastTestBundle(),
            new Blast\Bundle\BaseEntitiesBundle\BlastBaseEntitiesBundle(),
            new Blast\Bundle\UtilsBundle\BlastUtilsBundle(),

            // Attachments
            new Sil\Bundle\MediaBundle\SilMediaBundle(), 
          
            // Wisiwig editor
            new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),

            // your personal bundles
            // ...
        );
    }
    // ...
```

```
# app/config/routing.yml
admin:
    resource: '@SonataAdminBundle/Resources/config/routing/sonata_admin.xml'
    prefix: /
  
_sonata_admin:
    resource: .
    type: sonata_admin
    prefix: /

blast_core:
    resource: "@BlastCoreBundle/Resources/config/routing.yml" 
    prefix:   /admin

email:
    resource: "@SilEmailBundle/Resources/config/routing.yml"
    prefix: /admin
```

```
# app/config/config.yml
sonata_block:
    default_contexts: [cms]
    blocks:
        # Enable the SonataAdminBundle block
        sonata.admin.block.admin_list:
            contexts:   [admin]
        # Your other blocks
```

But please, refer to the source doc to get up-to-date :
https://sonata-project.org/bundles/admin/2-3/doc/reference/installation.html

Just notice that the ```prefix``` value is ```/``` instead of ```/admin``` as advised by the Sonata Project... By the way, it means that this access is universal, and not a specific "backend" interface. That's a specificity of a software package that intends to be focused on professional workflows.

Don't forget to publish assets as some features of the bundle such as file upload rely heavily on javascript.

Add the custom form field template to your config.yml:

```
# app/config/config.yml
twig:
    debug: '%kernel.debug%'
    strict_variables: '%kernel.debug%'
    form_themes:
        - 'SonataCoreBundle:Form:datepicker.html.twig'
        - 'SonataCoreBundle:Form:colorpicker.html.twig'
        - 'BlastCoreBundle:Admin/Form:fields.html.twig'
        - 'BlastUtilsBundle:Form:fields.html.twig'
        - 'BlastBaseEntitiesBundle:Form:fields.html.twig'
        - 'SilMediaBundle:Form:fields.html.twig'
```

### Spooling

You have to configure two mailers as follows in order to use the database spooling feature (one for direct sned, the other for spool send)

```
# app/config/config.yml

swiftmailer:
    default_mailer: direct_mailer
    mailers:
        direct_mailer:
            transport: "%mailer_transport%"
            host:      "%mailer_host%"
            username:  "%mailer_user%"
            password:  "%mailer_password%"
        spool_mailer:
            transport: "%mailer_transport%"
            host:      "%mailer_host%"
            username:  "%mailer_user%"
            password:  "%mailer_password%"
            spool: { type: db }
```
To flush the queue execute the command :
```$ app/console librinfo:spool:send```

Don't hesitate executing the command with --help as it has more options than the swiftmailer:spool:send

### Tracking

If you want to use the tracking functionalities, you need to add an access control rule to your security.yml that will allow anonymous users to access routes with the prefix '/tracking' like so:

```
# app/config/security.yml
access_control:
        # ...
        - { path: ^/tracking, role: IS_AUTHENTICATED_ANONYMOUSLY } # allow access to tracking controller for anonymous users
```

That's it !


[travis]: https://travis-ci.org/libre-informatique/EmailBundle
[coveralls]: https://coveralls.io/github/libre-informatique/EmailBundle?branch=master
[license]: ./LICENCE.md

