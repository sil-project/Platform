<?php

/*
 * This file is part of the Lisem Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Command;

use Sil\Bundle\SonataSyliusUserBundle\Entity\SonataUserInterface;
use Sylius\Bundle\CoreBundle\Command\AbstractInstallCommand;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Webmozart\Assert\Assert;

/**
 * This was initially taken from Sylius\Bundle\CoreBundle\Command\SetupCommand.
 *
 * main changes:
 * Sylius\Component\Core\Model\AdminUserInterface was replaced by
 * Sil\Bundle\SonataSyliusUserBundle\Entity\SonataUserInterface
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
final class SyliusSetupCommand extends AbstractInstallCommand
{
    /**
     * @var LocaleInterface
     */
    private $locale;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('lisem:sylius:setup')
            ->setDescription('Sylius configuration setup for the LiSem project.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command allows user to configure basic Sylius data for the LiSem project
(currency, locale, channel and administrator).
EOT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $currency = $this->get('lisem.sylius.setup.currency')->setup($input, $output, $this->getHelper('question'));
        $locale = $this->get('sylius.setup.locale')->setup($input, $output);
        $this->get('sylius.setup.channel')->setup($locale, $currency);
        $this->setupAdministratorUser($input, $output, $locale->getCode());
        $this->createPayboxPaymentMethod($input, $output);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param $localeCode
     *
     * @return int
     */
    protected function setupAdministratorUser(InputInterface $input, OutputInterface $output, $localeCode)
    {
        $output->writeln('Create your Sylius administrator account.');

        $userManager = $this->get('sylius.manager.admin_user');
        $userFactory = $this->get('sylius.factory.admin_user');

        try {
            $user = $this->configureNewUser($userFactory->createNew(), $input, $output);
        } catch (\InvalidArgumentException $exception) {
            return 0;
        }

        $user->setEnabled(true);
        $user->setLocaleCode($localeCode);

        $userManager->persist($user);
        $userManager->flush();

        $output->writeln('Sylius administrator account successfully registered.');
    }

    /**
     * @param UserInterface       $user
     * @param InputInterface      $input
     * @param OutputInterface     $output
     *
     * @return UserInterface
     */
    private function configureNewUser(UserInterface $user, InputInterface $input, OutputInterface $output)
    {
        $userRepository = $this->get('sylius.repository.admin_user');

        if ($input->getOption('no-interaction')) {
            Assert::notNull($userRepository->findOneByEmail('sylius@example.com'));

            $user->setEmail('sylius@example.com');
            $user->setPlainPassword('sylius');

            return $user;
        }

        $questionHelper = $this->getHelper('question');

        do {
            $question = $this->createEmailQuestion($output);
            $email = $questionHelper->ask($input, $output, $question);
            $exists = null !== $userRepository->findOneByEmail($email);

            if ($exists) {
                $output->writeln('<error>E-Mail is already in use!</error>');
            }
        } while ($exists);

        $user->setEmail($email);
        $user->setPlainPassword($this->getAdministratorPassword($input, $output));

        return $user;
    }

    /**
     * @param OutputInterface $output
     *
     * @return Question
     */
    private function createEmailQuestion(OutputInterface $output)
    {
        return (new Question('E-mail:'))
            ->setValidator(function ($value) use ($output) {
                /** @var ConstraintViolationListInterface $errors */
                $errors = $this->get('validator')->validate((string) $value, [new Email(), new NotBlank()]);
                foreach ($errors as $error) {
                    throw new \DomainException($error->getMessage());
                }

                return $value;
            })
            ->setMaxAttempts(3)
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     */
    private function getAdministratorPassword(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');
        $validator = $this->getPasswordQuestionValidator($output);

        do {
            $passwordQuestion = $this->createPasswordQuestion('Choose password:', $validator);
            $confirmPasswordQuestion = $this->createPasswordQuestion('Confirm password:', $validator);

            $password = $questionHelper->ask($input, $output, $passwordQuestion);
            $repeatedPassword = $questionHelper->ask($input, $output, $confirmPasswordQuestion);

            if ($repeatedPassword !== $password) {
                $output->writeln('<error>Passwords do not match!</error>');
            }
        } while ($repeatedPassword !== $password);

        return $password;
    }

    /**
     * @param OutputInterface $output
     *
     * @return \Closure
     */
    private function getPasswordQuestionValidator(OutputInterface $output)
    {
        return function ($value) use ($output) {
            /** @var ConstraintViolationListInterface $errors */
            $errors = $this->get('validator')->validate($value, [new NotBlank()]);
            foreach ($errors as $error) {
                throw new \DomainException($error->getMessage());
            }

            return $value;
        };
    }

    /**
     * @param string   $message
     * @param \Closure $validator
     *
     * @return Question
     */
    private function createPasswordQuestion($message, \Closure $validator)
    {
        return (new Question($message))
            ->setValidator($validator)
            ->setMaxAttempts(3)
            ->setHidden(true)
            ->setHiddenFallback(false)
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function createPayboxPaymentMethod(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('Creating Paybox payment method');

        $paymentMethod = $this->getContainer()->get('sylius.factory.payment_method')->createWithGateway('paybox');
        $paymentMethod->setCode('paybox');
        $paymentMethod->setName('Paybox');

        $config = $paymentMethod->getGatewayConfig();

        $config->setGatewayName('paybox');
        $config->setConfig([
            'identifiant' => $this->getContainer()->getParameter('paybox.identifiant'),
            'rang'        => $this->getContainer()->getParameter('paybox.rang'),
            'site'        => $this->getContainer()->getParameter('paybox.site'),
            'hmac'        => $this->getContainer()->getParameter('paybox.hmac'),
            'sandbox'     => $this->getContainer()->getParameter('paybox.sandbox'),
        ]);

        foreach ($this->getContainer()->get('sylius.repository.channel')->findAll() as $channel) {
            $paymentMethod->addChannel($channel);
        }

        $this->getContainer()->get('sylius.manager.payment_method')->persist($paymentMethod);
        $this->getContainer()->get('sylius.manager.payment_method')->flush();

        $output->writeln('Paybox payment method created');
    }
}
