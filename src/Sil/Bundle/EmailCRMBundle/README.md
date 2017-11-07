# SymfonySilEmailCRMBundle


[![Build Status](https://travis-ci.org/libre-informatique/EmailCRMBundle.svg?branch=master)](https://travis-ci.org/libre-informatique/EmailCRMBundle)
[![Coverage Status](https://coveralls.io/repos/github/libre-informatique/EmailCRMBundle/badge.svg?branch=master)](https://coveralls.io/github/libre-informatique/EmailCRMBundle?branch=master)
[![License](https://img.shields.io/github/license/libre-informatique/EmailCRMBundle.svg?style=flat-square)](./LICENCE.md)

[![Latest Stable Version](https://poser.pugx.org/libre-informatique/email-crm-bundle/v/stable)](https://packagist.org/packages/libre-informatique/email-crm-bundle)
[![Latest Unstable Version](https://poser.pugx.org/libre-informatique/email-crm-bundle/v/unstable)](https://packagist.org/packages/libre-informatique/email-crm-bundle)
[![Total Downloads](https://poser.pugx.org/libre-informatique/email-crm-bundle/downloads)](https://packagist.org/packages/libre-informatique/email-crm-bundle)

CRM bundle for Symfony with Email management

This bundle leverages the full potential of both [SymfonySilEmailBundle](https://github.com/libre-informatique/SymfonySilEmailBundle) and [SymfonySilCRMBundle](https://github.com/libre-informatique/SymfonySilCRMBundle)

It is also a proof of concept of how **it is possible** to override the entity mapping of a Symfony bundle, using the new Design Pattern "Outer Extension" (still a WIP in the Libre Informatique's lab, for the moment)! New article coming soon about how we did it...

## Usage

You have to implement 4 "outer extension" traits in your symfony AppBundle : 
* ContactExtension
* PositionExtension
* OrganismExtension
* EmailExtension

```php
// src/AppBundle/Entity/Extension/ContactExtension.php
namespace AppBundle\Entity\Extension;

trait ContactExtension
{
    use \Sil\Bundle\EmailCRMBundle\Entity\Traits\HasEmailMessages;
}

```

```php
// src/AppBundle/Entity/Extension/PositionExtension.php
namespace AppBundle\Entity\Extension;

trait PositionExtension
{
    use \Sil\Bundle\EmailCRMBundle\Entity\Traits\HasEmailMessages;
}

```

```php
// src/AppBundle/Entity/Extension/OrganismExtension.php
namespace AppBundle\Entity\Extension;

trait OrganismExtension
{
    use \Sil\Bundle\EmailCRMBundle\Entity\Traits\HasEmailMessages;
}

```

```php
// src/AppBundle/Entity/Extension/EmailExtension.php
namespace AppBundle\Entity\Extension;

trait EmailExtension
{
    use \Sil\Bundle\EmailCRMBundle\Entity\Traits\HasEmailRecipients;
}

```
... and now the entities of [SymfonySilEmailBundle](https://github.com/libre-informatique/SymfonySilEmailBundle) and 
[SymfonySilCRMBundle](https://github.com/libre-informatique/SymfonySilCRMBundle) are linked from outer space!
