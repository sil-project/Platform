# SymfonyBlastBaseEntitiesBundle

[![Build Status](https://travis-ci.org/blast-project/BaseEntitiesBundle.svg?branch=master)](https://travis-ci.org/blast-project/BaseEntitiesBundle)
[![Coverage Status](https://coveralls.io/repos/github/blast-project/BaseEntitiesBundle/badge.svg?branch=master)](https://coveralls.io/github/blast-project/BaseEntitiesBundle?branch=master)
[![License](https://img.shields.io/github/license/blast-project/BaseEntitiesBundle.svg?style=flat-square)](./LICENCE.md)

[![Latest Stable Version](https://poser.pugx.org/blast-project/base-entities-bundle/v/stable)](https://packagist.org/packages/blast-project/base-entities-bundle)
[![Latest Unstable Version](https://poser.pugx.org/blast-project/base-entities-bundle/v/unstable)](https://packagist.org/packages/blast-project/base-entities-bundle)
[![Total Downloads](https://poser.pugx.org/blast-project/base-entities-bundle/downloads)](https://packagist.org/packages/blast-project/base-entities-bundle)


This bundle provides some tools for a better integration of
[SilDoctrineBundle](https://github.com/libre-informatique/SymfonySilDoctrineBundle)
behaviours in
[Sonata Admin](https://sonata-project.org/bundles/admin/master/doc/index.html)

Installation
============

Prequiresites
-------------

- having a working Symfony2 environment
- having created a working Symfony2 app (including your DB and your DB link)
- having composer installed (here in /usr/local/bin/composer, with /usr/local/bin in the path)

Downloading
-----------

  $ composer require libre-informatique/base-entities-bundle dev-master

Adding the bundle in your app
-----------------------------

Edit your app/AppKernel.php file and add your "libre-informatique/base-entities-bundle" :

```php
    // app/AppKernel.php
    // ...
    public function registerBundles()
    {
        $bundles = array(
            // ...

            // The libre-informatique bundles
            new Blast\Bundle\BaseEntitiesBundle\BlastCoreBundle(),
            new Blast\Bundle\BaseEntitiesBundle\SilDoctrineBundle(),
            new Blast\Bundle\BaseEntitiesBundle\BlastBaseEntitiesBundle(),

            // your personal bundles
        );
    }
```
### Configuring the bundle

Activate the listeners you need in your application  (add those lines to ```app/config/config.yml```) :

```yml
# Enable SilDocrineBundle event listeners
librinfo_doctrine:
    orm:
        default:
            naming: true
            guidable: true
            timestampable: true
            addressable: true
            treeable: true
            nameable: true
            labelable: true
            emailable: true
            descriptible: true
            searchable: true
            loggable: true
    # List of entity search-able indexed fields
    entity_search_indexes:
        Me\MyBundle\Entity\MyEntity:
            fields:
                - name
                - fulltextName
                - firstname
                - lastname
                - description
                - email
                - url
```

Add/remove the needed behaviours for each orm connection used by your application.

Under `entity_search_indexes` key, you can set search indexes for each entity that should be indexable.

## Learn how to use the bundle

### Doctrine Behaviors provided by the bundle

- naming: provides a database table naming based on your vendor / bundle / entity
- guidable: provides GUID primary keys to your entities
- timestampable: provides createdAt / updatedAt fields and doctrine listeners to your entities
- addressable: provides address fields to your entities (address, city, zip, country...)
- treeable: provides tree structure to your entities
- nameable: provides a name field to your entities
- labelable: provides a label field to your entities
- emailable: provides email related fields to your entities
- descriptible: provides a description field to your entities
- searchable: provides a search index based on a selection of fields
- loggable:  tracks your entities changes and is able to manage versions

Learn how to use them, how they work, and by extension learn how to create new behaviors shaped to your needs, [reading the specific documentation](Resources/doc/base_entities_management.md).

Learn how to use the ```libre-informatique/base-entities-bundle```
==================================================================

Specific Form Types
-------------------

The bundle provides some form types, learn more about this, [reading the specific documentation](Resources/doc/README-FormTypes.md).
