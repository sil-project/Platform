# SymfonyLibrinfoDecoratorBundle

[![Build Status](https://travis-ci.org/libre-informatique/DecoratorBundle.svg?branch=master)](https://travis-ci.org/libre-informatique/DecoratorBundle)
[![Coverage Status](https://coveralls.io/repos/github/libre-informatique/DecoratorBundle/badge.svg?branch=master)](https://coveralls.io/github/libre-informatique/DecoratorBundle?branch=master)
[![License](https://img.shields.io/github/license/libre-informatique/DecoratorBundle.svg?style=flat-square)](./LICENCE.md)

[![Latest Stable Version](https://poser.pugx.org/libre-informatique/decorator-bundle/v/stable)](https://packagist.org/packages/libre-informatique/decorator-bundle)
[![Latest Unstable Version](https://poser.pugx.org/libre-informatique/decorator-bundle/v/unstable)](https://packagist.org/packages/libre-informatique/decorator-bundle)
[![Total Downloads](https://poser.pugx.org/libre-informatique/decorator-bundle/downloads)](https://packagist.org/packages/libre-informatique/decorator-bundle)





A basic decorator for various projects

## Installation

### Prerequiresites

having a working Symfony2 environment

* having created a working Symfony2 app (including your DB and your DB link)
* having composer installed (here in /usr/local/bin/composer, having /usr/local/bin in your path)

Optional:

* having libre-informatique/core-bundle installed, or if not, follow the README instructions of the Core bundle

### Downloading

From your project root directory:

```$ composer require libre-informatique/crm-bundle dev-master```

### The Sonata bundles

Do not forget to configure the SonataAdminBundle. Find examples in the [libre-informatique/crm-bundle](https://github.com/libre-informatique/SymfonyLibrinfoCRMBundle#the-sonata-bundles) documentation.

### The Libre Informatique bundles

Do not forget to configure the BlastCoreBundle. Find examples in the [libre-informatique/core-bundle](https://github.com/libre-informatique/SymfonyLibrinfoCRMBundle#the-libre-informatique-bundles) documentation.

### Finish

Consider ```LibrinfoDecoratorBundle``` an assetic bundle :

```
# app/config/config.yml
# ...
assetic:
    bundles:
        # ...
        - LibrinfoDecoratorBundle
# ...
```

Publish the assets :

```$ app/console assets:install --symlink```

## The goal of this bundle

This bundle aims to ease the personnalization, in a reusable and scalable way, of your projects, giving something like a framework, once for all of them.

It works as a man-in-the-middle technology that overloads the default configuration, without stealing the right, for a project, to precise it again. By the way, this bundle reduces the size of the global configuration of a project.

## Improving the LibrinfoDecoratorBundle

The basics is to be able to extend in a very smoothy way the SonataAdminBundle, replacing its default parameters in a single YAML file named ```Resources/config/decorator.yml```, and using the Twig philosophy of inheritance and blocks.

### decorator.yml

```
# Resources/config/decorator.yml
parameters:
    librinfo_decorator:
        templates:
            layout:
                original: [SonataAdminBundle::standard_layout.html.twig, BlastCoreBundle::standard_layout.html.twig]
                modified: LibrinfoDecoratorBundle::layout.html.twig
```

You have noticed the root of this bundle's parameters : ```librinfo_decorator```.
Then if you need to replace generically some templates, you'll need to configure which default configuration you want to replace, to avoid overloading the project direct configuration.

So, you'll have to specify the Sonata key (from ```sonata_admin``` root element in your ```app/config/config.yml```, see [the Sonata documentation](https://sonata-project.org/bundles/admin/2-3/doc/reference/configuration.html)) and two sub-keys :

* ```original```: an array of which values you allow LibrinfoDecoratorBundle to erase
* ```modified```: the value which will replace what you wrote in the ```original``` sub-key
 
### The templates

Once you've replaced some of the default configuration templates by new ones you'll be able, if the new ones are located within the LibrinfoDecoratorBundle, to define them in the ```Resources/views/``` directory. There you'll use the Twig directives of ```extends```, ```{{ parent() }}```, ```{% block xxx %}``` to be more efficient.

e.g.:

```
{# Resources/views/layout.html.twig #}
{#

This file is part of the Libre Informatique CoreBundle package.

(c) Baptiste SIMON <baptiste.simon _AT_ libre-informatique.fr>
(c) Gildas LE MOAL <gildas.lemoal _AT_ libre-informatique.fr>
(c) Libre Informatique [http://www.libre-informatique.fr/]

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.

#}

{% extends 'BlastCoreBundle::standard_layout.html.twig' %}

{% block body_attributes %}

class="sonata-bc skin-blue fixed"

{% endblock %}

{% block side_bar_after_nav %}

{% endblock %}

{% block sonata_top_nav_menu %}

<div class="navbar-right">
    <ul class="nav navbar-nav">
        <li class="dropdown user-menu">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                {% include admin_pool.getTemplate('user_block') %}
            </ul>
        </li>
    </ul>
</div>

{% endblock %}

{% block logo %}
    {% spaceless %}
    <a class="logo" href="{{ url('sonata_admin_dashboard') }}">
        {% if 'single_image' == admin_pool.getOption('title_mode') or 'both' == admin_pool.getOption('title_mode') %}
            {% if 'bundles/sonataadmin/logo_title.png' == admin_pool.titlelogo %}
            <img src="{{ asset('bundles/librinfodecorator/logo-li.png') }}" alt="Libre Informatique">
            {% else %}
            <img src="{{ asset(admin_pool.titlelogo) }}" alt="{{ admin_pool.title }}">
            {% endif %}
        {% endif %}
        {% if 'single_text' == admin_pool.getOption('title_mode') or 'both' == admin_pool.getOption('title_mode') %}
            {% if 'Sonata Admin' == admin_pool.title %}
            <span>Libre Informatique</span>
            {% else %}
            <span>{{ admin_pool.title }}</span>
            {% endif %}
        {% endif %}
    </a>
    {% endspaceless %}
{% endblock %}
```
