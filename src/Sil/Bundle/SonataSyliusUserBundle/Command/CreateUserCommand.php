<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SonataSyliusUserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

// TODO...
// TODO...

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
            ->setName('librinfo:user:create')
            ->setDescription('Create a Sonata admin user.')
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

        if ($input->getOption('no-interaction')) {
            $exists = null !== $userRepository->findOneByEmail('librinfo@example.com');

            if ($exists) {
                return 0;
            }

            $user->setEmail('librinfo@example.com');
            $user->setPlainPassword('librinfo');
        } else {
            do {
                $email = $this->ask($input, $output, 'E-Mail:');
                $exists = null !== $userRepository->findOneByEmail($email);

                if ($exists) {
                    $output->writeln('<error>E-Mail is already in use!</error>');
                }
            } while ($exists);

            $user->setEmail($email);
            $user->setPlainPassword($this->getAdministratorPassword($input, $output));
        }

        $user->setEnabled(true);
        $code = trim($this->getContainer()->getParameter('locale'));
        $user->setLocaleCode($code);

        $userManager->persist($user);
        $userManager->flush();
        $output->writeln('Administrator account successfully registered.');
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
