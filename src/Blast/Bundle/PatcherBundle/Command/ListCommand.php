<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\PatcherBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Blast\Bundle\PatcherBundle\Command\Helper\Config;
use Blast\Bundle\PatcherBundle\Command\Helper\Logger;

class ListCommand extends ContainerAwareCommand
{
    use Config,
        Logger;

    protected function configure()
    {
        $this
            ->setName('blast:patchs:list')
            ->setDescription('List Patches from Librinfo on misc vendors');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->loadConfig();

        $this->info("\nListing available patches:\n\n");
        $this->info('  - - - -  ');
        foreach ($this->config['patches'] as $patch) {
            $this->info('id: ', false);
            $this->comment($patch['id']);
            $this->info('enabled: ', false);
            $this->comment($patch['enabled'] ? 'true' : 'false');
            $this->info('targetFile: ', false);
            $this->comment($patch['targetFile']);
            $this->info('patchFile: ', false);
            $this->comment($patch['patchFile']);
            $this->info('  - - - -  ');
        }

        $this->displayMessages($output);
    }
}
