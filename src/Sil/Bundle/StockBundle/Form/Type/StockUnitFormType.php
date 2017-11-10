<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Sil\Bundle\StockBundle\Domain\Repository\LocationRepositoryInterface;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class StockUnitFormType extends BaseType
{
    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @param LocationRepositoryInterface $locationRepository
     */
    public function __construct(LocationRepositoryInterface $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $locations = $this->locationRepository->findAll();

        $builder->add('location', EntityType::class,
            [
                'label'        => false,
                'class'        => 'Sil\Bundle\StockBundle\Domain\Entity\Location',
                'choices'      => $locations,
                'choice_label' => 'name',
        ]);

        $builder->add('qty', UomQtyFormType::class);
    }

    public function getBlockPrefix()
    {
        return 'stock_unit_form';
    }

    public function getName()
    {
        return 'stock_unit_form';
    }
}
