<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\DataFixtures\Sylius\Factory;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\Options;
use Webmozart\Assert\Assert;
use Sil\Bundle\SonataSyliusUserBundle\Entity\SonataUserInterface;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ExampleFactory
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function setCreator($object, $userName = 'DataFixtures')
    {
        $repository = $this->entityManager->getRepository(SonataUserInterface::class);
        $user = $repository->findOneByUsername($userName);
        Assert::notNull($user, sprintf('Could not find SonataUser with user name "%s"', $userName));
        if (method_exists($object, 'setCreatedBy')) {
            $object->setCreatedBy($user);
            $object->setUpdatedBy($user);
        }
    }

    protected function generateCode($codeGenerator, $object)
    {
        $codeGenerator::generate($object);
    }

    /**
     * @param EntityRepository $repository
     * @param string           $field
     *
     * @return \Closure
     */
    protected function findOneBy(EntityRepository $repository, $field)
    {
        return function (Options $options, $previousValue) use ($repository, $field) {
            if (null === $previousValue || [] === $previousValue) {
                return $previousValue;
            }

            if (is_object($previousValue)) {
                return $previousValue;
            } else {
                return $repository->findOneBy([$field => $previousValue]);
            }
        };
    }

    /**
     * @param EntityRepository $repository
     *
     * @return \Closure
     */
    protected function randomOne(EntityRepository $repository)
    {
        return function (Options $options) use ($repository) {
            $objects = $repository->findAll();

            if ($objects instanceof Collection) {
                $objects = $objects->toArray();
            }

            Assert::notEmpty($objects);

            return $objects[array_rand($objects)];
        };
    }
}
