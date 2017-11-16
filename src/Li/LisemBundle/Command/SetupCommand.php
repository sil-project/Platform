<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Command;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use LisemBundle\Entity\SilEcommerceBundle\Product;
use Sil\Bundle\SonataSyliusUserBundle\Entity\SonataUserInterface;
use Sil\Bundle\CRMBundle\Entity\City;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Initializes the database for LiSem project.
 *
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class SetupCommand extends ContainerAwareCommand
{
    private $csvDir;

    protected function configure()
    {
        $this
            ->setName('lisem:install:setup')
            ->setDescription('LiSem configuration setup.')
            ->setDefinition(
                new InputDefinition([
                    new InputOption('with-samples', null, InputOption::VALUE_NONE, 'Load sample data fixture'),
                    new InputOption('csv-dir', null, InputOption::VALUE_OPTIONAL, 'CSV data directory path'),
                    new InputOption('yes', null, InputOption::VALUE_NONE, 'Answer yes to confirmation (for with-samples option)'),
                ])
            )
            ->setHelp(<<<EOT
The <info>%command.name%</info> command allows user to configure basic LiSem application data.

Examples:

    * Basic configuration (will not purge database):
    <info>%command.name%</info>

    * With random data:
    <info>%command.name% --with-samples</info>

    * With CSV data:
    <info>%command.name% --with-samples --csv-dir=/my/csv/dir/path</info>
EOT
            )
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @todo  Exception handling
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->csvDir = $input->getOption('csv-dir');

        if ($this->csvDir) {
            if (!$input->getOption('with-samples')) {
                throw new \Exception('The "csv-dir" option requires the "sample-data" option');
            }
            if (!is_dir($this->csvDir)) {
                throw new \Exception('Could not find directory: ' . $this->csvDir);
            }
        }

        if ($input->getOption('with-samples')) {
            $output->writeln(['', '<question>This will erease the existing data.</question>']);
            if (!$input->getOption('yes')) {
                $helper = $this->getHelper('question');
                $question = new ConfirmationQuestion('Continue with this action [y|N] ? ', false);
                if (!$helper->ask($input, $output, $question)) {
                    return;
                }
            }

            $this->purgeDatabase($output);

            $this->setupSylius($output);
            $this->setupUsers($output);
            $this->setupCircles($output);
            $this->setupProductOptions($output);
            $this->setupData($output, ['lisem_requirements']);
            $this->setupSampleData($output);
            $this->setupCities($output);
            $this->setupAssets($output);
        } else {
            $this->setupSylius($output);
            $this->setupUsers($output);
            $this->setupCircles($output);
            $this->setupProductOptions($output);
            $this->setupData($output, ['lisem_requirements']);
            $this->setupCities($output);
            $this->setupAssets($output);
        }
    }

    /**
     * Run command: app/console sylius:install:setup --no-interaction.
     *
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function setupSylius(OutputInterface $output)
    {
        $output->writeln(['', 'Running <info>lisem:sylius:setup --no-interaction</info> command...']);
        $command = $this->getApplication()->find('lisem:sylius:setup');
        $commandInput = new ArrayInput(['--no-interaction' => true]);

        return $command->run($commandInput, $output);
    }

    /**
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function setupUsers(OutputInterface $output)
    {
        $output->writeln(['', 'Setting up application users...']);

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $repo = $em->getRepository(SonataUserInterface::class);

        $warn = false;
        if (!$this->getContainer()->getParameter('lisem.user.datafixtures')) {
            $warn = 'Parameter lisem.user.datafixtures is not defined.';
        } else {
            $users = $this->getContainer()->getParameter('lisem.user.datafixtures');
            if (empty($users)) {
                $warn = 'Parameter lisem.user.datafixtures is empty.';
            }
        }
        if ($warn) {
            $output->writeln(['<comment>', $warn, 'See app/config/parameters.yml.dist for an example.', '</comment>']);

            return 1;
        }

        $created = false;
        foreach ($users as $u) {
            $output->write(sprintf('%s <%s>', $u['username'], $u['email']));
            if (null !== $repo->findOneByUsername($u['email'])) {
                $output->writeln(' <info>exists</info>');
                continue;
            }
            $sonataUserClass = $this->getContainer()->getParameter('sil_sonata_sylius_user.entity.sonata_user.class');
            $sonataUser = new $sonataUserClass();
            $sonataUser->setUsername($u['email']);
            $sonataUser->setUsernameCanonical($u['email']);
            $sonataUser->setPlainPassword($u['password']);
            $sonataUser->setEmail($u['email']);
            $sonataUser->setEnabled(true);
            $sonataUser->addRole('ROLE_SUPER_ADMIN');
            $sonataUser->addRole('ROLE_ADMINISTRATION_ACCESS');
            $sonataUser->setLocaleCode('fr_FR');
            $em->persist($sonataUser);
            $created = true;
            $output->writeln(' <info>added</info>');
        }

        if ($created) {
            $em->flush();
        }

        return 0;
    }

    /**
     * Run command: app/console sil:crm:init-circles.
     *
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function setupCircles(OutputInterface $output)
    {
        $output->writeln(['', 'Running <info>sil:crm:init-circles</info> command...']);
        $command = $this->getApplication()->find('sil:crm:init-circles');
        $circlesInput = new ArrayInput([]);

        return $command->run($circlesInput, $output);
    }

    /**
     * Create application product application.
     *
     * @param OutputInterface $output
     *
     * @return int
     *
     * @todo Hardcoded product options should be moved to application params (same way as initCircles...)
     */
    protected function setupProductOptions(OutputInterface $output)
    {
        $output->writeln(['', 'Initializing application specific <info>Product Options</info>...']);
        $options = [
            ['code' => Product::$PACKAGING_OPTION_CODE,  'name' => 'Conditionnement', 'type' => 'text', 'values' => [
                'BULK' => ['locale' => 'fr_FR', 'value' => 'Vrac'],
                '1G'   => ['locale' => 'fr_FR', 'value' => '1g'],
                '5G'   => ['locale' => 'fr_FR', 'value' => '5g'],
                '20S'  => ['locale' => 'fr_FR', 'value' => '20 graines'],
                '50S'  => ['locale' => 'fr_FR', 'value' => '50 graines'],
            ]],
        ];

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $optionRepository = $this->getContainer()->get('sylius.repository.product_option');
        $optionFactory = $this->getContainer()->get('sylius.factory.product_option');
        $optionValueRepository = $this->getContainer()->get('sylius.repository.product_option_value');
        $optionValueFactory = $this->getContainer()->get('sylius.factory.product_option_value');

        foreach ($options as $o) {
            $option = $optionRepository->findOneByCode($o['code']);
            if (!$option) {
                $output->write(sprintf('Creating product option "%s"', $o['code']));
                $option = $optionFactory->createNew();
                $option->setCode($o['code']);
                $option->setName($o['name']);
                $optionRepository->add($option);
            } else {
                $output->write(sprintf('Updating product option "%s"', $o['code']));
                $option->setName($o['name']);
                $em->persist($option);
            }

            if (!empty($o['values'])) {
                foreach ($o['values'] as $code => $v) {
                    $optionValue = $optionValueRepository->findOneByCode($code);
                    if (!$optionValue || !$option->hasValue($optionValue)) {
                        $output->write(sprintf(' - Creating product option value "%s"', $code));
                        $optionValue = $optionValueFactory->createNew();
                        $optionValue->setCode($code);
                        $optionValue->setValue($v['value']);
                        $optionValue->setFallbackLocale($v['locale']);
                        $optionValue->setCurrentLocale($v['locale']);
                        $option->addValue($optionValue);
                        $optionValueRepository->add($optionValue);
                    } else {
                        $output->write(sprintf(' - Updating product option value "%s"', $code));
                        $optionValue->setValue($v['value']);
                        $optionValue->setFallbackLocale($v['locale']);
                        $optionValue->setCurrentLocale($v['locale']);
                        $em->persist($optionValue);
                    }
                }
            }

            $em->flush();
            $output->writeln('<info> done.</info>');
        }

        return 0;
    }

    /**
     * Create application product attributes
     * Not used anymore. Left here as an example...
     *
     * @param OutputInterface $output
     *
     * @return int
     *
     * @todo Hardcoded attributes should be moved to application params (same way as initCircles...)
     */
    protected function setupProductAttributes(OutputInterface $output)
    {
        $output->writeln(['', 'Initializing application specific <info>Product Attributes</info>...']);
        $attributes = [
            ['code' => Product::$PACKAGING_OPTION_CODE,  'name' => 'Conditionnement', 'type' => 'text'],
        ];
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $repository = $this->getContainer()->get('sylius.repository.product_attribute');
        $factory = $this->getContainer()->get('sylius.factory.product_attribute');
        $registry = $this->getContainer()->get('sylius.registry.attribute_type');

        foreach ($attributes as $a) {
            $attribute = $repository->findOneByCode($a['code']);
            if (!$attribute) {
                $output->write(sprintf('Creating product attribute with code "%s"', $a['code']));
                $attribute = $factory->createTyped($a['type']);
                $attribute->setCode($a['code']);
                $attribute->setName($a['name']);
                $repository->add($attribute);
            } else {
                $output->write(sprintf('Updating product attribute with code "%s"', $a['code']));
                $storageType = $registry->get($a['type'])->getStorageType();
                $attribute->setCode($a['code']);
                $attribute->setName($a['name']);
                $attribute->setType($a['type']);
                $attribute->setStorageType($storageType);
                $em->persist($attribute);
                $em->flush();
            }
            $output->writeln('<info> done.</info>');
        }

        return 0;
    }

    /**
     * @param OutputInterface $output
     *
     * @todo  Optimize the "clean" way of importing cities
     */
    protected function setupCities(OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $conn = $em->getConnection();
        $file = __DIR__ . '/../DataFixtures/ORM/Cities/france.csv';
        $output->writeln(['', sprintf('Importing <info>cities</info> from %s...', realpath($file))]);

        try {
            // This is not clean, but it is FAST:
            $conn->exec('TRUNCATE TABLE sil_crm_city');
            $num_rows_effected = $conn->exec("COPY sil_crm_city (id, country_code, city, zip) FROM '$file' delimiter ',';");
            $output->writeln(sprintf('<info> done (%d cities).</info>', $num_rows_effected));
        } catch (\Exception $e) {
            // This is clean but it is SLOW:
            $output->writeln(sprintf('<info> Using fallback method (SLOW) to import cities</info>'));
            $em->getConnection()->getConfiguration()->setSQLLogger(null);
            $em->createQuery('DELETE FROM SilCRMBundle:City')->execute();
            $handle = fopen($file, 'r');
            $i = 0;
            $batchSize = 200;
            while (($line = fgetcsv($handle)) !== false) {
                ++$i;
                $city = new City();
                $city->setCountryCode($line[1]);
                $city->setCity($line[2]);
                $city->setZip($line[3]);
                $em->persist($city);
                if (($i % $batchSize) === 0) {
                    $em->flush();
                    $em->clear();
                }
            }
            fclose($handle);
            $em->flush();
            $em->clear();
            $output->writeln(sprintf('<info> done (%d cities).</info>', $i));
        }
    }

    /**
     * @param OutputInterface $output
     */
    protected function purgeDatabase(OutputInterface $output)
    {
        $output->writeln('');
        $output->write('Purging database...');
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $purger = new ORMPurger($em);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE); // Seems faster than PURGE_MODE_TRUNCATE
        $purger->purge();
        $output->writeln('<info> done.</info>');
    }

    /**
     * @param OutputInterface $output
     */
    protected function setupSampleData(OutputInterface $output)
    {
        $output->writeln(['', 'Running <info>doctrine:fixtures:load --append --fixtures=src/Li/LisemBundle/DataFixtures</info> command...']);
        $fixturesCommand = $this->getApplication()->find('doctrine:fixtures:load');
        $fixturesInput = new ArrayInput([
            '--append'   => true,
            '--fixtures' => 'src/Li/LisemBundle/DataFixtures',
        ]);
        $fixturesInput->setInteractive(false);
        $fixturesCommand->run($fixturesInput, $output);

        $fixturesSuites = [
            'lisem_default',
            'lisem_varieties',
            'lisem_products',
        ];

        $this->setupData($output, $fixturesSuites);
    }

    protected function setupData(OutputInterface $output, array $fixturesSuites)
    {
        foreach ($fixturesSuites as $suite) {
            if ($suite == 'lisem_varieties' && $this->csvDir) {
                $fixturesCommand = $this->getApplication()->find('lisem:import:csv');
                $output->writeln(['', 'Running <info>lisem:import:csv ' . $this->csvDir . '</info> command...']);
                $fixturesInput = new ArrayInput([
                    'dir' => $this->csvDir,
                ]);
                $fixturesInput->setInteractive(false);
                $fixturesCommand->run($fixturesInput, $output);
            } else {
                $fixturesCommand = $this->getApplication()->find('sylius:fixtures:load');
                $output->writeln(['', "Running <info>sylius:fixtures:load $suite</info> command..."]);
                $fixturesInput = new ArrayInput([
                    'suite' => $suite,
                ]);
                $fixturesInput->setInteractive(false);
                $fixturesCommand->run($fixturesInput, $output);
            }
            $output->writeln('<info>done.</info>');
        }
    }

    /**
     * @param OutputInterface $output
     */
    protected function setupAssets(OutputInterface $output)
    {
        $output->writeln(['', 'Running <info>sylius:theme:assets:install --symlink</info> command...']);
        $themeCommand = $this->getApplication()->find('sylius:theme:assets:install');
        $themeInput = new ArrayInput([
            '--symlink' => true,
        ]);
        $themeCommand->run($themeInput, $output);

        $output->writeln(['', 'Running <info>assets:install --symlink</info> command...']);
        $asseticCommand = $this->getApplication()->find('assets:install');
        $asseticInput = new ArrayInput([
            '--symlink' => true,
        ]);
        $asseticInput->setInteractive(false);
        $asseticCommand->run($asseticInput, $output);
    }
}
