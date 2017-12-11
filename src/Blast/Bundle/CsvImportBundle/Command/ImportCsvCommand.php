<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\CsvImportBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
     * @var array
     */
    protected $importClass = [];

    protected function configure()
    {
        $this
        ->setName('blast:import:csv')
        ->setDescription('Import data from CSV files into Blast.')
        ->addArgument('dir', InputArgument::REQUIRED, 'The directory containing the CSV files.')
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
        $this->dir = $input->getArgument('dir');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @todo: import class should be load from config file */
        foreach ($this->importClass as $class) {
            $this->importData($class, $output);
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

        $normalizer = $this->getContainer()->get('blast_csv_import.mapping.configuration'); // new ObjectNormalizer($entityClass, $this->em);
        $serializer = new Serializer([$normalizer, new ArrayDenormalizer()], [new CsvEncoder()]);
        $objects = $serializer->deserialize($data, $entityClass . '[]', 'csv');
        $output->writeln(sprintf(' <info>%d objects</info>', count($objects)));

        $rc = new \ReflectionClass($entityClass);
        $method = 'postDeserialize' . $rc->getShortName();

        foreach ($objects as $k => $object) {
            if (method_exists($this, $method)) {
                $this->{$method}($object);
            }

            $this->em->persist($object);

            if ($k % 50 == 0) {
                $this->em->flush();
            }
        }
        $this->em->flush();
        $output->writeln('DONE (' . basename($csv) . ')...');
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
        $this->mapping = $this->getContainer()->get('blast_csv_import.mapping.configuration')->getMapping(); //CsvMappingConfiguration::getInstance()->getMapping();

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
