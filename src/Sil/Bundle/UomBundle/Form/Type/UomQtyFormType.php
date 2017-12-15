<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\UomBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormInterface;
use Sil\Component\Uom\Repository\UomRepositoryInterface;
use Sil\Component\Uom\Model\UomQty;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class UomQtyFormType extends BaseType
{
    /**
     * @var UomRepositoryInterface
     */
    protected $uomRepository;

    /**
     * @param UomRepositoryInterface $manager
     */
    public function __construct(UomRepositoryInterface $uomRepository)
    {
        $this->uomRepository = $uomRepository;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'attr'       => ['class' => 'form-inline'],
            'data_class' => 'Sil\Component\Uom\Model\UomQty',
            'empty_data' => function (FormInterface $form) {
                return new UomQty(
                    $form->get('uom')->getData(),
                    floatval($form->get('value')->getData())
                );
            },
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            [$this, 'buildUomTypeChoices']);

        $builder->add('value', NumberType::class,
            [
                'required' => true,
                'attr'     => ['placeholder' => 'Quantité'],
                'label'    => false,
            ]);
    }

    public function buildUomTypeChoices(FormEvent $event)
    {
        $choices = [];
        $uoms = null;

        $uoms = $this->uomRepository->findAll();

        foreach ($uoms as $uom) {
            $choices[$uom->getName()] = $uom->getId();
        }

        $form->add('uom', EntityType::class,
            [
                'label'        => false,
                'class'        => 'Sil\Bundle\UomBundle\Entity\Uom',
                'choices'      => $uoms,
                'choice_label' => 'name',
        ]);
    }

    public function getBlockPrefix()
    {
        return self::class;
    }
}
