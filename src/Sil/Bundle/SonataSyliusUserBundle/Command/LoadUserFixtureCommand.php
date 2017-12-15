<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SonataSyliusUserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class LoadUserFixtureCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sil:user:fixture')
            ->setDescription('Load User from fixture.')
            ->setDefinition(
                new InputDefinition([
                    new InputOption('super', null, InputOption::VALUE_NONE, 'promote as admin'),
                    new InputOption('fixture', null, InputOption::VALUE_REQUIRED, 'fixture to load', 'sil.user.fixtures'),
                ])
            )
            ->setHelp(<<<EOT
The <info>%command.name%</info> command allows to load user from fixture.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Load user account.');

        foreach ($this->getContainer()->getParameter(
            $input->getOption('fixture')
        ) as $user) {
            $super = ($input->getOption('super')) ? true : $user['super'];

            $output->writeln(sprintf(
                'Load %s <%s>, as admin : %s',
                $user['username'],
                $user['email'],
                ($super) ? 'yes' : 'no'
            ));

            $createCommand = $this->getApplication()->find('sil:user:create');
            $createInput = new ArrayInput([
                '--email'          => $user['email'],
                '--username'       => $user['username'],
                '--password'       => $user['password'],
                '--no-interaction' => true,
            ]);
            $createCommand->run($createInput, $output);

            if ($super) {
                $superCommand = $this->getApplication()->find('sil:user:promote');
                $superInput = new ArrayInput([
                    'email'            => $user['email'],
                    'role'             => 'ROLE_SUPER_ADMIN',
                    '--no-interaction' => true,
                ]);
                $superCommand->run($superInput, $output);
            }
        }
        $output->writeln('Account(s) successfully registered.');
    }
}
