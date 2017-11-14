<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SonataSyliusUserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class PromoteUserCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sil:user:promote')
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of existing user')
            ->addArgument('role', InputArgument::OPTIONAL, 'The role to be granted to user')
            ->setDescription('Promote a user to specifyed role.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command allows to promote a user.
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Promote user account.');

        $userManager = $this->get('sylius.manager.sonata_user');
        $userRepository = $this->get('sylius.repository.sonata_user');

        $user = null;

        $email = $input->getArgument('email');
        $role = $input->getArgument('role');

        if (!$input->getOption('no-interaction')) {
            do {
                $email = $this->ask($input, $output, 'E-Mail:');
                $exists = ($userRepository->findOneByEmail($email) !== null);
                if (!$exists) {
                    $output->writeln('<error>User with this email does not exists</error>');
                } else {
                    $user = $exists;
                }
            } while (!$exists);
        }

        if (!$input->getOption('no-interaction')) {
            do {
                $role = $this->ask($input, $output, 'Role:');
                $exists = $this->roleExists($role);
                if (!$exists) {
                    $output->writeln('<error>This role does not exists</error>');
                }
            } while (!$exists);
        }

        /** @var AdminUserInterface $user */
        $user = $userRepository->findOneByEmail($email);

        if ($input->getOption('no-interaction')) {
            if (!$user) {
                return 0;
            }
        } else {
            $user->addRole($role);
        }

        $userManager->persist($user);
        $userManager->flush();
        $output->writeln('User account successfully promoted.');
    }

    protected function roleExists($role)
    {
        $roles = $this->getContainer()->getParameter('security.role_hierarchy.roles');

        $exists = array_key_exists($role, $roles);

        if (!$exists) {
            foreach ($roles as $subRoles) {
                if (in_array($role, $subRoles)) {
                    $exists = true;
                    break;
                }
            }
        }

        return $exists;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param string          $question
     *
     * @return mixed
     */
    protected function ask(InputInterface $input, OutputInterface $output, $question)
    {
        $helper = $this->getHelperSet()->get('question');

        return $helper->ask($input, $output, new Question($question));
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     */
    private function getAdministratorPassword(InputInterface $input, OutputInterface $output)
    {
        do {
            $password = $this->ask($input, $output, 'Choose password:');
            $repeatedPassword = $this->ask($input, $output, 'Confirm password:');

            if ($repeatedPassword !== $password) {
                $output->writeln('<error>Passwords do not match!</error>');
            }
        } while ($repeatedPassword !== $password);

        return $password;
    }

    /**
     * @param $id
     *
     * @return object
     */
    protected function get($id)
    {
        return $this->getContainer()->get($id);
    }
}
