<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Sil\Component\Contact\Repository\CountryRepositoryInterface;
use Sil\Bundle\ContactBundle\Entity\Country;

/**
 * Name to Country transformer.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class NameToCountryTransformer implements DataTransformerInterface
{
    /**
     * Country repository.
     *
     * @var CountryRepositoryInterface
     */
    private $repository;

    /**
     * @param CountryRepositoryInterface $repository
     */
    public function __construct(CountryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Transforms a Country name into a Country instance.
     *
     * @param  $name
     *
     * @return array
     */
    public function transform($name)
    {
        return $this->repository->findOneBy(['name' => $name]);
    }

    /**
     * @param $id
     *
     * @return Country
     *
     * @throws TransformationFailedException
     */
    public function reverseTransform($country)
    {
        return $country->getName();
    }
}
