# TestsBundle


[![Build Status](https://travis-ci.org/blast-project/TestsBundle.svg?branch=master)](https://travis-ci.org/blast-project/TestsBundle)
[![Coverage Status](https://coveralls.io/repos/github/blast-project/TestsBundle/badge.svg?branch=master)](https://coveralls.io/github/blast-project/TestsBundle?branch=master)
[![License](https://img.shields.io/github/license/blast-project/TestsBundle.svg?style=flat-square)](./LICENCE.md)

[![Latest Stable Version](https://poser.pugx.org/blast-project/tests-bundle/v/stable)](https://packagist.org/packages/blast-project/tests-bundle)
[![Latest Unstable Version](https://poser.pugx.org/blast-project/tests-bundle/v/unstable)](https://packagist.org/packages/blast-project/tests-bundle)
[![Total Downloads](https://poser.pugx.org/blast-project/tests-bundle/downloads)](https://packagist.org/packages/blast-project/tests-bundle)


Extends Symfony KernelTestCase with BlastTestCase and allow you to quick access some usefull routine...


Installation
============

Downloading
-----------

  $ composer require blast-project/tests-bundle


Example
-------

Check services blast*

```php

use Blast\TestsBundle\Functional\BlastTestCase;

class BlastServiceTest extends BlastTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }
    
    public function testServicesAreInitializable()
    {
        $this->isServicesAreInitializable('blast');
    }
}
```
Launch console command:
```php
use Blast\TestsBundle\Functional\BlastTestCase;

class CollectGarbageCommandTest extends BlastTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }
    
    public function testCommand()
    {
      $this->cacheClear();
      // or
      $this->launchCommand([
            'command' => 'cache:clear',
            '--no-warmup' => true,
      ]);
    }
}

```