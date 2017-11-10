<?php

/*
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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use Sil\Bundle\EcommerceBundle\Form\DataTransformer\ArrayCollectionTransformer;

class AnyChannelsType extends AbstractType
{
    private $em;

    private $channelClass;

    public function __construct(EntityManagerInterface $em, $channelClass, $anyClass)
    {
        $this->em = $em;
        $this->channelClass = $channelClass;
        $this->anyClass = $anyClass;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // parent::configureOptions($resolver);
        $repo = $this->em->getRepository($this->channelClass);
        $qb = $repo->createQueryBuilder('o');

        $channels = $qb->getQuery()->getResult();

        $resolver->setDefaults(
            [
            'choices'      => $channels,
            'class'        => $this->anyClass,
            'choice_value' => 'id',
            'choice_label' => 'code',
            'label'        => false,
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
        return 'sil_type_any_channels';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ArrayCollectionTransformer();
        $builder->addModelTransformer($transformer);
    }
}
