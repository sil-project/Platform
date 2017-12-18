<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace  Sil\Bundle\SonataSyliusUserBundle\Tests\Functional;

use Blast\Bundle\TestsBundle\Functional\BlastTestCase;

/** @todo: add some test for command to load user **/
class SonataSyliusUserServicesTest extends BlastTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testServicesAreInitializable()
    {
        $this->isServicesAreInitializable('sil_sonata_sylius_user.admin.sonata_user');
    }
}
