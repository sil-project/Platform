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

namespace Blast\Bundle\DoctrineSessionBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CollectGarbageCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('blast:session:collect-garbage')
            ->setDescription('Deletes sessions older.')
            ->addOption('all', 'a', InputOption::VALUE_NONE, 'Will clear ALL sessions')
            ->addArgument('limit', InputArgument::OPTIONAL, 'Sessions expired {limit} ago will be removed. (examples: 1 hour, 5 days)');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sessionClass = $this->getContainer()->getParameter('blast_doctrine_session_class');

        $qb = $this
            ->getContainer()
            ->get('doctrine')
            ->getManagerForClass($sessionClass)
            ->getRepository($sessionClass)
            ->createQueryBuilder('s')
            ->delete()
            ;

        if (!$input->getOption('all')) {
            if (empty($input->getArgument('limit'))) {
                $input->setArgument('limit', '1 hour');
            }

            $limit = new \DateTime();
            $limit->sub(\DateInterval::createFromDateString($input->getArgument('limit')));

            $qb
                ->where($qb->expr()->lt('s.expiresAt', ':limit'))
                ->setParameter('limit', $limit)
                ;
        }

        $qb
            ->getQuery()
            ->execute()
            ;
    }
}
