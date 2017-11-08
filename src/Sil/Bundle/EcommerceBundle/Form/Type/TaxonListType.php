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

namespace Sil\Bundle\EcommerceBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;

class TaxonListType extends AbstractType
{
    private $em;

    private $taxonClass;

    public function __construct(EntityManagerInterface $em, $taxonClass)
    {
        $this->em = $em;
        $this->taxonClass = $taxonClass;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $repo = $this->em->getRepository($this->taxonClass);
        $qb = null;
        if (method_exists($repo, 'createListQueryBuilder')) {
            $qb = $repo->createListQueryBuilder('o');
        } else {
            $qb = $repo->createQueryBuilder('o');
        }

        $qb->orderBy('o.root', 'ASC');
        $qb->orderBy('o.left', 'ASC');

        $taxons = $qb->getQuery()->getResult();

        array_walk(
            $taxons, function (&$item) {
                $item->displayName = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $item->getLevel()) . $item->getName();
            }
        );

        $resolver->setDefaults(
            [
                'choices'      => $taxons,
                'no_cart'      => false,
                'choice_value' => 'id',
            ]
        );
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'sil_type_taxon_list';
    }
}
