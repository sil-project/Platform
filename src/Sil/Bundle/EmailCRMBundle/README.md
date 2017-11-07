# SymfonyLibrinfoEmailCRMBundle


[![Build Status](https://travis-ci.org/libre-informatique/EmailCRMBundle.svg?branch=master)](https://travis-ci.org/libre-informatique/EmailCRMBundle)
[![Coverage Status](https://coveralls.io/repos/github/libre-informatique/EmailCRMBundle/badge.svg?branch=master)](https://coveralls.io/github/libre-informatique/EmailCRMBundle?branch=master)
[![License](https://img.shields.io/github/license/libre-informatique/EmailCRMBundle.svg?style=flat-square)](./LICENCE.md)

[![Latest Stable Version](https://poser.pugx.org/libre-informatique/email-crm-bundle/v/stable)](https://packagist.org/packages/libre-informatique/email-crm-bundle)
[![Latest Unstable Version](https://poser.pugx.org/libre-informatique/email-crm-bundle/v/unstable)](https://packagist.org/packages/libre-informatique/email-crm-bundle)
[![Total Downloads](https://poser.pugx.org/libre-informatique/email-crm-bundle/downloads)](https://packagist.org/packages/libre-informatique/email-crm-bundle)

CRM bundle for Symfony with Email management

This bundle leverages the full potential of both [SymfonyLibrinfoEmailBundle](https://github.com/libre-informatique/SymfonyLibrinfoEmailBundle) and [SymfonyLibrinfoCRMBundle](https://github.com/libre-informatique/SymfonyLibrinfoCRMBundle)

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
    use \Librinfo\EmailCRMBundle\Entity\Traits\HasEmailMessages;
}

```

```php
// src/AppBundle/Entity/Extension/PositionExtension.php
namespace AppBundle\Entity\Extension;

trait PositionExtension
{
    use \Librinfo\EmailCRMBundle\Entity\Traits\HasEmailMessages;
}

```

```php
// src/AppBundle/Entity/Extension/OrganismExtension.php
namespace AppBundle\Entity\Extension;

trait OrganismExtension
{
    use \Librinfo\EmailCRMBundle\Entity\Traits\HasEmailMessages;
}

```

```php
// src/AppBundle/Entity/Extension/EmailExtension.php
namespace AppBundle\Entity\Extension;

trait EmailExtension
{
    use \Librinfo\EmailCRMBundle\Entity\Traits\HasEmailRecipients;
}

```
... and now the entities of [SymfonyLibrinfoEmailBundle](https://github.com/libre-informatique/SymfonyLibrinfoEmailBundle) and 
[SymfonyLibrinfoCRMBundle](https://github.com/libre-informatique/SymfonyLibrinfoCRMBundle) are linked from outer space!
