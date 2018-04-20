<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\TestsBundle\Tests\Functional;

use Blast\Bundle\TestsBundle\Functional\BlastTestCase;

class ServicesTest extends BlastTestCase
{
    public function testServicesAreInitializable()
    {
        $this->isServicesAreInitializable('blast');

        $this->isServicesAreInitializable('sil');
    }
}
