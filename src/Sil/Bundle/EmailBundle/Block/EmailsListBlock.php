<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EmailBundle\Block;

use Doctrine\ORM\EntityManager;
use Sil\Bundle\EmailBundle\Entity\Email;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\TextBlockService;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Response;

class EmailsListBlock extends TextBlockService
{
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @param EntityManager $manager
     */
    public function setManager(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $settings = $blockContext->getSettings();
        $targetEntity = $settings['target_entity'];
        $maxResults = $settings['max_results'];
        $emails = $this->getEmails($targetEntity, $maxResults);

        return $this->renderResponse($blockContext->getTemplate(), array(
            'block'    => $blockContext->getBlock(),
            'settings' => $settings,
            'emails'   => $emails,
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'content'       => 'Insert your custom content here',
            'template'      => 'SilEmailBundle:Block:block_emails_list.html.twig',
            'target_entity' => null,
            'max_results'   => 20,
        ));
    }

    /**
     * @param object $targetEntity
     * @param int    $maxResults
     *
     * @return array
     *
     * @throws \Exception
     */
    private function getEmails($targetEntity, $maxResults)
    {
        if (!$targetEntity || !is_object($targetEntity)) {
            return [];
        }
        $rc = new \ReflectionClass($targetEntity);
        if (!$rc->hasProperty('emailMessages')) {
            return [];
        }

        $repo = $this->manager->getRepository($rc->getName());
        if (method_exists($repo, 'getEmailMessagesQueryBuilder')) {
            $qb = $repo->getEmailMessagesQueryBuilder($targetEntity->getId());
        } else {
            $repo = $this->manager->getRepository(Email::class);
            $targets = strtolower($rc->getShortName()) . 's'; // ex. contacts
            $qb = $repo->createQueryBuilder('e')
                ->leftJoin('e.' . $targets, 't')
                ->where('t.id = :targetid')
                ->setParameter('targetid', $targetEntity->getId())
            ;
        }
        $qb->orderBy('e.updatedAt', 'desc')
            ->setMaxResults($maxResults);

        return $qb->getQuery()->getResult();
    }
}
