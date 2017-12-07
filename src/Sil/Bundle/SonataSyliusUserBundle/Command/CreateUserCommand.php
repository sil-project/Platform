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
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 *
 * @todo   Add validation
 */
class CreateUserCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sil:user:create')
            ->setDescription('Create a Sonata admin user.')
            ->setDefinition(
                new InputDefinition([
                    new InputOption('username', null, InputOption::VALUE_REQUIRED, 'UserName', 'sil-user'),
                    new InputOption('password', null, InputOption::VALUE_REQUIRED, 'PassWord', 'sil'),
                    new InputOption('email', null, InputOption::VALUE_REQUIRED, 'EmailAdress', 'sil-user@sil.eu'),
                ])
            )
            ->setHelp(<<<EOT
The <info>%command.name%</info> command allows to create a user.
EOT
            )
            ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Create an administrator account.');

        $userManager = $this->get('sylius.manager.sonata_user');
        $userRepository = $this->get('sylius.repository.sonata_user');
        $userFactory = $this->get('sylius.factory.sonata_user');

        /** @var AdminUserInterface $user */
        $user = $userFactory->createNew();

        $username = $input->getOption('username');
        $password = $input->getOption('password');
        $email = $input->getOption('email');
        $localeCode = trim($this->getContainer()->getParameter('locale'));

        if (!$input->getOption('no-interaction')) {
            $username = $this->ask($input, $output, 'Username', $username);
            $email = $this->ask($input, $output, 'E-Mail', $email);
            $password = $this->getAdministratorPassword($input, $output, $password);
        }

        $exists = null !== $userRepository->findOneByEmail($email);

        if ($exists) {
            $output->writeln('<error>E-Mail is already in use!</error>');

            return 1;
        }

        $user->setUsername($username);
        $user->setUsernameCanonical($username);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled(true);
        $user->setLocaleCode($localeCode);
        $userManager->persist($user);
        $userManager->flush();
        $output->writeln(sprintf('Account %s (%s) successfully registered.', $username, $email));
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param string          $question
     *
     * @return mixed
     */
    protected function ask(InputInterface $input, OutputInterface $output, $question, $answer)
    {
        $helper = $this->getHelperSet()->get('question');

        return $helper->ask($input, $output, new Question($question . " [$answer]:", $answer));
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     */
    private function getAdministratorPassword(InputInterface $input, OutputInterface $output, $answer)
    {
        do {
            $password = $this->ask($input, $output, 'Choose password', $answer);
            $repeatedPassword = $this->ask($input, $output, 'Confirm password', $answer);

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
