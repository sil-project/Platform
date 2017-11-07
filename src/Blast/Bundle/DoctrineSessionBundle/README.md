# Blast DoctrineSessionBundle

[![Build Status](https://travis-ci.org/blast-project/DoctrineSessionBundle.svg?branch=master)](https://travis-ci.org/blast-project/DoctrineSessionBundle)
[![Coverage Status](https://coveralls.io/repos/github/blast-project/DoctrineSessionBundle/badge.svg?branch=master)](https://coveralls.io/github/blast-project/DoctrineSessionBundle?branch=master)
[![License](https://img.shields.io/github/license/blast-project/DoctrineSessionBundle.svg?style=flat-square)](./LICENCE.md)

[![Latest Stable Version](https://poser.pugx.org/blast-project/doctrine-session-bundle/v/stable)](https://packagist.org/packages/blast-project/doctrine-session-bundle)
[![Latest Unstable Version](https://poser.pugx.org/blast-project/doctrine-session-bundle/v/unstable)](https://packagist.org/packages/blast-project/doctrine-session-bundle)
[![Total Downloads](https://poser.pugx.org/blast-project/doctrine-session-bundle/downloads)](https://packagist.org/packages/blast-project/doctrine-session-bundle)



The goal of this bundle is to make the use of Doctrine as session handler for Symfony

Installation
============

Downloading
-----------

  $ composer require blast-project/doctrine-session-bundle


Add to your AppKernel
---------------------

```php
//...

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            //...

            new Blast\Bundle\DoctrineSessionBundle\BlastDoctrineSessionBundle(),

        ];
        //...
    }
    //...
}
```

Doctrine
--------

Configure your Doctrine connections

```yml
# app/config/config.yml

doctrine:
    dbal:
        default_connection: default
        connections:
            default:
                driver:   pdo_pgsql
                host:     "%database_host%"
                port:     "%database_port%"
                dbname:   "%database_name%"
                user:     "%database_user%"
                password: "%database_password%"
                charset:  UTF8
            session: # This will be the connection used by this bundle
                driver:   pdo_pgsql
                host:     "%database_host%" # Please adapt to your needs if you're using another database for sessions
                port:     "%database_port%"
                dbname:   "%database_name%"
                user:     "%database_user%"
                password: "%database_password%"
                charset:  UTF8

    orm:
        default_entity_manager: default
        auto_generate_proxy_classes: "%kernel.debug%"
        entity_managers:
            default:
                connection: default
                naming_strategy: doctrine.orm.naming_strategy.underscore
                auto_mapping: true
                mappings:
                    # Add here your mapping for your default entities
                    # Example below :
                    BlastBaseEntitiesBundle:
                        type: yml
            session:
                connection: session
                naming_strategy: doctrine.orm.naming_strategy.underscore
                auto_mapping: false # Only one entity manager can have auto_mappping set to true
                mappings:
                    BlastDoctrineSessionBundle:
                        type: yml
```

Configure the framework session handler :

```yml
# app/config/config.yml

framework:
    # [...]
    session:
        handler_id: blast_doctrine_handler
```


Database
--------

  $ php bin/console doctrine:database:create --connection=session

Or

  $ php bin/console doctrine:schema:update --force --connection=session

Usage
-----
```php

use Blast\Bundle\DoctrineSessionBundle\Handler\DoctrineORMHandler;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

//...

        $doctrinehandler = new DoctrineORMHandler(
                         $this->get('doctrine'),
                         'Blast\Bundle\DoctrineSessionBundle\Entity\Session');

        $storage = new NativeSessionStorage(
                 array(),
                 $doctrinehandler
        );

        $session = new Session($storage);
        $session->start();
//...

```

See
---

http://symfony.com/doc/current/components/http_foundation/session_configuration.html
