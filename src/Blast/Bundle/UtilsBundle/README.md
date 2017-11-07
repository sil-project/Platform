# Blast UtilsBundle

[![Build Status](https://travis-ci.org/blast-project/UtilsBundle.svg?branch=master)](https://travis-ci.org/blast-project/UtilsBundle)
[![Coverage Status](https://coveralls.io/repos/github/blast-project/UtilsBundle/badge.svg?branch=master)](https://coveralls.io/github/blast-project/UtilsBundle?branch=master)
[![License](https://img.shields.io/github/license/blast-project/UtilsBundle.svg?style=flat-square)](./LICENCE.md)

[![Latest Stable Version](https://poser.pugx.org/blast-project/utils-bundle/v/stable)](https://packagist.org/packages/blast-project/utils-bundle)
[![Latest Unstable Version](https://poser.pugx.org/blast-project/utils-bundle/v/unstable)](https://packagist.org/packages/blast-project/utils-bundle)
[![Total Downloads](https://poser.pugx.org/blast-project/utils-bundle/downloads)](https://packagist.org/packages/blast-project/utils-bundle)

## Features

### Blast Choices

Documentation to be writen

### Blast Hooks

This bundle introduce a hook feature that is really basic hook management.

You can define in your views any hook you want.

#### Declare the hook target location in a view

```html
{# myTemplate.html.twig #}

<div>
    <h1>Here my custom hook</h1>
    {{ blast_hook('my.custom.hook', {'someParameters': myVar}) }}
</div>
```

A hook can be declared without using any parameters. If so, the « hook block » won't have any parameters defined in  `handleParameters`'s method parameter (var `$hookParameters` will be an empty array).

#### Declare your Hook class

This class will manage rendering of the hook content by setting `view parameters` (act as a controller)

```php
<?php

namespace MyBundle\Hook\MyCustomHook;

use Blast\UtilsBundle\Hook\AbstractHook;

class MyCustomHookExample extends AbstractHook
{
    protected $hookName = 'my.custom.hook';
    protected $template = 'MyBundle:Hook:my_custom_hook_example.html.twig';

    public function handleParameters($hookParameters)
    {
        $this->templateParameters = [
            'someViewParameter' => 'a value that will be passed to the twig view'
        ];
    }
}
```

*Note: you can get the current hook name (configured in service definition) in attribute `AbstractHook::hookName` ans the configured template in `AbstractHook::template`*

#### Register the hook class as service

```yml
    my_bundle.hook.my_custom_hook_example:
        class: MyBundle\Hook\MyCustomHook\MyCustomHookExample
        tags:
            - { name: blast.hook, hook: my.custom.hook, template: MyBundle:Hook:my_custom_hook_example.html.twig }
```

The hook configuration are sets in the service tag :
- `name`: the service tag name (must be `blast.hook`)
- `hook`: the target hook where the « block » will be rendered
- `template`: the twig template of the « block »

_Please don't forget the tag `blast.hook` in order to register your service as a hook_

#### Create your hook template

```html
{# MyBundle:Hook:my_custom_hook_example.html.twig #}

<p>
    Here's my first custom hook,  with a view var : {{ someViewParameter }} !
</p>
```

And voila, you should have this rendered content :

```html
<div>
    <h1>Here my custom hook</h1>
    <p>
        Here's my first custom hook,  with a view var : a value that will be passed to the twig view !
    </p>
</div>
```

### Blast Custom Filters

Enable the feature in config.yml

```yml
# app/config/config.yml
blast_utils:
    features:
        customFilters:
            enabled: true
```

Optionnaly, you can define your own customFilter entity by setting it as below (don't forget to set the associated repository in order to override `createNewCustomFilter` method) :

```yml
# app/config/config.yml
blast_utils:
    features:
        customFilters:
            enabled: true
            class: MyBundle\Entity\MyCustomFilter
```

You only have to set your User class entity in application config.yml (see https://symfony.com/doc/current/doctrine/resolve_target_entity.html for more informations)

```yml
# app/config/config.yml
doctrine:
    # ...
    orm:
        # ...
        resolve_target_entities:
            Blast\CoreBundle\Model\UserInterface: MyBundle\Entity\MyUser
```

If you're using Sylius, setting the doctrine.orm `resolve_target_entities` key will not work because Sylius is already using this system. You can declare your Interface / Entity replacement within `SyliusResource` configuration :

```yml
# app/config/config.yml
sylius_resource:
    resources:
        blast.utils: # this is an arbitrary key
            classes:
                model: MyBundle\Entity\MyUser
                interface: Blast\CoreBundle\Model\UserInterface
```

### Blast User Interface

In order to set User mapping with utils entity, the mapping with Interface is used.

There are 2 ways for configuring the real class that will replace the UserInterface :

#### Using Sylius

declare, via resources, the class that will replace the model interface

```yml
sylius_resource:
    resources:
        blast.utils:
            classes:
                model: MyBundle\Entity\MyRealUser
                interface: Blast\CoreBundle\Model\UserInterface
```

#### Using Syfony's Doctrine target entity resolver :

```yml
doctrine:
    # ...
    orm:
        #...
        resolve_target_entities:
            Blast\CoreBundle\Model\UserInterface: MyBundle\Entity\MyRealUser
```
