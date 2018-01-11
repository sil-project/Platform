<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UtilsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BlastHookLocateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('blast:hook:locate')
            ->setDescription('List used blast hook in twig views');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Listing declared blast hooks');

        $projectDir = $this->getContainer()->get('kernel')->getProjectDir();

        $grepCmd = 'grep -Rn \'{{ blast_hook(\' vendor src app/Resources';

        ob_start();
        system($grepCmd);
        $out = ob_get_clean();

        $out = explode("\n", trim($out));

        array_walk($out, function (&$object, $key) use (&$out) {
            $explodedArray = explode(':', $object, 3);
            if (substr($explodedArray[0], -4) == 'twig') {
                if (isset($explodedArray[2])) {
                    $explodedArray[2] = trim(preg_replace('/\{\{\W?blast_hook\(\'([a-zA-Z0-9\.\_]*)\'.*/', '$1', $explodedArray[2]));
                }

                $object = $explodedArray;
            } else {
                unset($out[$key]);
            }
        });

        if (count($out) > 0) {
            $io->table(
                ['file', 'line', 'hook_name'],
                $out
            );
        } else {
            $io->text('No hooks have been found');
        }
    }
}
