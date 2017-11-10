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

use Symfony\Component\Form\AbstractType;
use Sil\Bundle\StockBundle\Domain\Repository\LocationRepositoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Description of LocationChoiceFormType.
 *
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class LocationChoiceFormType extends AbstractType
{
    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    public function __construct(LocationRepositoryInterface $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $repo = $this->getLocationRepository();
        $locations = $repo->findAll();

        $resolver->setDefaults(
            [
                'choices'      => $locations,
                'choice_label' => 'getIndentedName',
                'choice_value' => 'id',
            ]
        );
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getLocationRepository(): LocationRepositoryInterface
    {
        return $this->locationRepository;
    }
}
