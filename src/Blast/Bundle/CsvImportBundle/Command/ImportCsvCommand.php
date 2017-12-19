<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\CsvImportBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ImportCsvCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string Directory where the CSV files are located
     */
    protected $dir;

    /**
     * @var array Mapping configuration of csv datas
     */
    private $mapping;

    /**
     * @var ObjectNormalizer
     */
    private $normalizer;

    /**
     * @var array
     */
    protected $importClass = [];

    /**
     * @var array
     */
    protected $codeList = [];

    protected function configure()
    {
        $this
            ->setName('blast:import:csv')
            ->setDescription('Import data from CSV files into Blast.')
            ->setDefinition(
                new InputDefinition([
                new InputOption(
                    'mapping',
                    'm',
                    InputOption::VALUE_REQUIRED,
                    'The mapping files.',
                    'src/Resources/config/csv_import.yml'
                ),
                new InputOption(
                    'dir',
                    'd',
                    InputOption::VALUE_REQUIRED,
                    'The path directory containing the CSV files.',
                    'src/Resources/data'
                ),
                ])
            )

        ->setHelp(<<<EOT
The <info>%command.name%</info> command allows user to populate Database with CSV data files.
EOT
        )
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->em = $this->getContainer()->get('doctrine')->getEntityManager();
        $this->dir = $input->getOption('dir');
        $mappingConfig = $this->getContainer()
                       ->get('blast_csv_import.mapping.configuration')
                       ->loadMappingFromFile($input->getOption('mapping'));
        $this->mapping = $mappingConfig->getMapping();
        $this->importClass = $mappingConfig->getImportClass();
        $this->normalizer = $this->getContainer()->get('blast_csv_import.normalizer.object');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->importClass as $entityClass) {
            if (class_exists($entityClass)) {
                $this->beforeImport($entityClass, $output);
                $this->importData($entityClass, $output);
            } else {
                $output->writeln(sprintf('%s does not exist', $entityClass));
            }
        }
    }

    /**
     * @todo move this to a Csv Import Data service
     */
    protected function importData($entityClass, OutputInterface $output)
    {
        $output->write("Importing <info>$entityClass</info>");
        $csv = $this->getCsvFilePath($entityClass);
        $output->write(' (' . basename($csv) . ')...');
        $data = file_get_contents($csv);

        /* @todo: allow import from other format (or not) */
        $serializer = new Serializer([$this->normalizer, new ArrayDenormalizer()], [new CsvEncoder()]);

        /* @todo: should not use the . [] and update setAttributeValue from Normalizer */
        $objects = $serializer->deserialize($data, $entityClass . '[]', 'csv');

        $output->writeln(sprintf(' <info>%d objects</info>', count($objects)));

        // $rc = new \ReflectionClass($entityClass);
        // $method = 'postDeserialize' . $rc->getShortName();

        $this->codeList = []; // Init table of code
        foreach ($objects as $k => $object) {
            // if (method_exists($this, $method)) {
            //    $this->{$method}($object);
            //}
            $this->postDeserialize($entityClass, $object, $output);

            $this->em->persist($object);

            // Hum Lol
            if ($k % 1000 == 0) {
                $this->em->flush();
            }
        }
        $this->em->flush();
        $output->writeln('DONE (' . basename($csv) . ')...');
    }

    protected function postDeserialize($entityClass, $object, OutputInterface $output)
    {
        if (array_key_exists('generators', $this->mapping[$entityClass])) {
            foreach (keys($this->mapping[$entityClass]['generators']) as $field) {
                $generator = $this->getContainer()->get($this->mapping[$entityClass]['generators'][$field]);
                $code = $generator->generate($object, $codeList);
                $object->set[$field]($code);
                $codeList[] = $code;
            }
        }
    }

    /**
     * @param string $entityClass
     */
    protected function beforeImport($entityClass, OutputInterface $output)
    {
        $doDelete = false;
        if (array_key_exists('delete', $this->mapping[$entityClass])) {
            $doDelete = $this->mapping[$entityClass]['delete'];
        }
        if ($doDelete) {
            $output->writeln(sprintf('Delete from %s', $entityClass));
            $em = $this->getContainer()->get('doctrine')->getEntityManager();

            $em->createQuery('DELETE FROM ' . $entityClass)->execute();
            //$em->createQuery('DELETE FROM :entityClass')->execute(['entityClass', $entityClass]);
        }
    }

    /**
     * @param string $entityClass
     *
     * @return string
     *
     * @throws \Exception
     */
    protected function getCsvFilePath($entityClass)
    {
        /* @todo: remove this check as key come from mapping */
        if (!key_exists($entityClass, $this->mapping)) {
            throw new \Exception('Entity class not supported: ' . $entityClass);
        }

        $csv = $this->dir . '/' . $this->mapping[$entityClass]['filename'];
        if (!file_exists($csv)) {
            throw new \Exception('File not found: ' . $csv);
        }
        if (!is_readable($csv)) {
            throw new \Exception('File not readable: ' . $csv);
        }

        return $csv;
    }
}
