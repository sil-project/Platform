<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Searchable;

/**
 * Batch update of search indexes.
 *
 * @author Marcos Bezerra de Menezes <marcos.bezerra at libre-informatique.fr>
 *
 * @todo update all search indexes for a namespace or for a bundle
 *          it could be based on GenerateEntitiesDoctrineCommand, but it is not yet PSR-4 compatible
 *          see : https://github.com/doctrine/DoctrineBundle/issues/282
 */
class UpdateSearchCommand extends DoctrineCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('blast:update:search')
            ->setDescription('Batch update of search indexes')
            ->addArgument('name', InputArgument::REQUIRED, 'Entity FQDN class name or Doctrine alias')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command updates the search indexes for a single entity :

  <info>php %command.full_name% MyCustomBundle:User</info>
  <info>php %command.full_name% MyCustomBundle/Entity/User</info>

EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = strtr($input->getArgument('name'), '/', '\\');

        if (false !== $pos = strpos($name, ':')) {
            $name = $this->getContainer()->get('doctrine')->getAliasNamespace(substr($name, 0, $pos)) . '\\' . substr($name, $pos + 1);
        }

        if (class_exists($name)) {
            $output->writeln(sprintf('Updating search index for entity "<info>%s</info>"', $name));
        } else {
            throw new \RuntimeException(sprintf('%s class doesn\'t exist.', $name));
        }

        // Check if the entity has the Searchable trait
        $reflector = new \ReflectionClass($name);
        $traits = $reflector->getTraitNames();
        if (!in_array('Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Searchable', $traits)) {
            throw new \RuntimeException(sprintf('%s class doesn\'t have the Searchable trait.', $reflector->getName()));
        }

        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $metadata = $em->getClassMetadata($name);

        $searchHandler = $this->getContainer()->get('blast_base_entities.search_handler');
        $searchHandler->handleEntity($metadata);
        $searchHandler->batchUpdate();
        $output->writeln('DONE');
    }
}
