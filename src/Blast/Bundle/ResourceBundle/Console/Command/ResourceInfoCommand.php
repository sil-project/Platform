<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\ResourceBundle\Console\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Blast\Component\Resource\Metadata\MetadataRegistryInterface;
use Blast\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Description of ListDoctrineTablesCommand.
 *
 * @author glenn
 */
class ResourceInfoCommand extends ContainerAwareCommand
{
    /**
     * @var MetadataRegistryInterface
     */
    private $registry;

    /**
     * @param MetadataRegistryInterface $registry
     */
    public function __construct(MetadataRegistryInterface $registry)
    {
        parent::__construct();
        $this->metadataRegistry = $registry;
    }

    protected function configure()
    {
        $this
          ->setName('blast:resource:info')
          ->setDescription('List Blast resources');
        $this->addArgument('resource', InputArgument::OPTIONAL, 'Resource to debug');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $resourceAlias = $input->getArgument('resource');
        $io = new SymfonyStyle($input, $output);

        if (null === $resourceAlias) {
            $this->listResources($io);

            return;
        }
        $metadata = $this->getMetadataRegistry()->get($resourceAlias);
        $this->debugResource($metadata, $io);
    }

    /**
     * @param OutputInterface $output
     */
    private function listResources(OutputStyle $io)
    {
        $resources = $this->getMetadataRegistry()->getAll();
        ksort($resources);
        $headers = [new TableCell('Prefix'), new TableCell('Alias')];
        $rows = [];
        foreach ($resources as $resource) {
            $rows[] = [$resource->getPrefix(), $resource->getAlias()];
        }
        $io->table($headers, $rows);
    }

    /**
     * @param MetadataInterface $metadataMetadataInterface
     * @param OutputInterface   $output
     */
    private function debugResource(MetadataInterface $metadata, OutputStyle $io)
    {
        $io->title($metadata->getFullyQualifiedName());

        $headers = [new TableCell('Classes', array('colspan' => 2))];
        $rows = [
            $this->formatStringRow('model', $metadata->getClassMap()->getModel()),
            $this->formatStringRow('repository', $metadata->getClassMap()->getRepository()),
            $this->formatArrayRow('interfaces', $metadata->getClassMap()->getInterfaces(), null, false),
            $this->formatStringRow('controller', $metadata->getClassMap()->getController()),
        ];
        $io->table($headers, $rows);

        if ($metadata->hasRouting()) {
            //  $this->debugRouting($metadata, $io);
        }
    }

    protected function debugRouting(MetadataInterface $metadata, OutputStyle $io)
    {
        $routing = $metadata->getRouting();
        $view = $routing->getView();

        $headers = [new TableCell('Routing', array('colspan' => 2))];
        $rows = [];
        $actions = $routing->getActions();
        if (count($actions)) {
            $firstKey = key($actions);
            $separator = [[new TableSeparator(), new TableSeparator()]];

            foreach ($actions as $k => $action) {
                $rows = ($k == $firstKey ? $rows : array_merge($rows, $separator));
                $rows = array_merge($rows, [
                $this->formatStringRow('path', $action->getPath()),
                $this->formatArrayRow('methods', $action->getMethods(), ['ANY']),
            ]);
            }
        }

        $io->table($headers, $rows);
    }

    private function formatStringRow(string $key, ?string $data)
    {
        $value = $data;
        if (null == $value || empty($value)) {
            $value = '~';
        }

        return [$key, $value];
    }

    private function formatArrayRow(string $key, ?array $data, ?array $default, $inline = true)
    {
        $value = $data;

        if (null == $value || empty($value)) {
            $value = $default ?? ['~'];
        }

        if ($inline) {
            $value = implode(',', $value);
        } else {
            $value = implode("\n", $value);
        }

        return [$key, $value];
    }

    private function getMetadataRegistry()
    {
        return $this->metadataRegistry;
    }
}
