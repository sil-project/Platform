<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace  Blast\Bundle\BlastCsvImportBundle\Tests\Functional;

use Blast\Bundle\TestsBundle\Functional\BlastTestCase;

class BlastCsvImportCommandTest extends BlastTestCase
{
    protected $testResourcesPath;

    protected function setUp()
    {
        parent::setUp();
        $this->testResourcesPath = $this->locator->locate('@BlastTestsBundle/Resources');
        //  $this->registrymanager = static::$kernel->getContainer()->get('doctrine');
    }

    /* @todo: use relative path to TestBundle Resource dir */
    /* @todo: check database after load */

    public function testSimpleLoad()
    {
        $this->launchCommand([
            'command'          => 'blast:import:csv',
            '--dir'            => $this->testResourcesPath . '/import',
            '--mapping'        => $this->testResourcesPath . '/config/csv_import_simple.yml',
            '--no-interaction' => true,
            '--env'            => 'test',
        ]);
    }

    public function testSecondLoad()
    {
        $this->launchCommand([
            'command'          => 'blast:import:csv',
            '--dir'            => $this->testResourcesPath . '/import',
            '--mapping'        => $this->testResourcesPath . '/config/csv_import_second.yml',
            '--no-interaction' => true,
            '--env'            => 'test',
        ]);
    }

    public function testParentLoad()
    {
        $this->launchCommand([
            'command'          => 'blast:import:csv',
            '--dir'            => $this->testResourcesPath . '/import',
            '--mapping'        => $this->testResourcesPath . '/config/csv_import_parent.yml',
            '--no-interaction' => true,
            '--env'            => 'test',
        ]);
    }

    public function testFinalLoad()
    {
        $this->launchCommand([
        'command'          => 'blast:import:csv',
        '--dir'            => $this->testResourcesPath . '/import',
        '--mapping'        => $this->testResourcesPath . '/config/csv_import_final.yml',
        '--no-interaction' => true,
        '--env'            => 'test',
        ]);
    }
}
