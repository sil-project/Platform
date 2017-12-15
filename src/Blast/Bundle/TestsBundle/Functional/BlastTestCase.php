<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\TestsBundle\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Tester\CommandTester;

class BlastTestCase extends KernelTestCase
{
    protected $container;
    protected $application;
    protected $input;
    protected $output;
    protected $command;

    protected function setUp()
    {
        static::bootKernel();

        /* @var Container $container */
        $this->container = self::$kernel->getContainer();
    }

    /** Service Test **/
    protected function isServicesAreInitializable($srvname)
    {
        $serviceIds = array_filter(
            $this->container->getServiceIds(),
            function ($serviceId) use ($srvname) {
                return 0 === strpos($serviceId, $srvname);
            }
        );

        foreach ($serviceIds as $serviceId) {
            $this->assertNotNull($this->container->get($serviceId));
        }
    }

    /** Command Test **/
    protected function launchCommand(array $cmdargs)
    {
        $this->application = new Application(self::$kernel);

        /*
         * @todo : check find result before continue
         */
        $this->command = $this->application->find($cmdargs['command']);

        /* @todo find why or why not CommandTester */
        //        $this->command = new CommandTester($this->application->find($cmdargs['command']));

        $this->application->add($this->command);
        if (in_array(['--no-interaction'], $cmdargs)) {
            $cmdargs['--no-interaction'] = true;
        }
        $this->input = new ArrayInput($cmdargs);
        $this->output = new ConsoleOutput();

        /**
         * @todo: add a try catch
         */
        $res = $this->command->run($this->input, $this->output);

        $this->assertEquals(0, $res);

        return $res;
    }

    /**
     * @todo move command alias to a trait (or not) (maybe one for cache and one for doctrine...)
     */
    protected function cacheClear()
    {
        return $this->launchCommand([
            'command'     => 'cache:clear',
            '--no-warmup' => true,
        ]);
    }

    protected function dropDatabase()
    {
        return $this->launchCommand([
            'command'     => 'doctrine:database:drop',
            '--if-exists' => true,
            '--force'     => true,
        ]);
    }

    protected function createDatabase()
    {
        return $this->launchCommand([
            'command'         => 'doctrine:database:create',
            '--if-not-exists' => true,
          ]);
    }

    protected function createSchema()
    {
        return $this->launchCommand([
            'command' => 'doctrine:schema:create',
        ]);
    }

    protected function validateSchema()
    {
        return $this->launchCommand([
            'command' => 'doctrine:schema:validate',
        ]);
    }

    protected function updateSchema()
    {
        return $this->launchCommand([
            'command' => 'doctrine:schema:update',
            '--force' => true,
        ]);
    }
}
