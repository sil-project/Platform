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
use Sil\Bundle\ContactBundle\Entity\Address;
use Sil\Bundle\ContactBundle\Entity\City;
use Sil\Component\Contact\Repository\CountryRepositoryInterface;
use Sil\Component\Contact\Repository\ProvinceRepositoryInterface;

/**
 * Array to City transformer.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class ArrayToCityTransformer implements DataTransformerInterface
{
    /**
     * Country repository.
     *
     * @var CountryRepositoryInterface
     */
    private $countryRepository;

    /**
     * Province repository.
     *
     * @var ProvinceRepositoryInterface
     */
    private $provinceRepository;

    /**
     * @param CountryRepositoryInterface  $countryRepository
     * @param ProvinceRepositoryInterface $provinceRepository
     */
    public function __construct(CountryRepositoryInterface $countryRepository, ProvinceRepositoryInterface $provinceRepository)
    {
        $this->countryRepository = $countryRepository;
        $this->provinceRepository = $provinceRepository;
    }

    /**
     * Transforms a City instance into an array.
     *
     * @param  $city
     *
     * @return array
     */
    public function transform($city)
    {
        if (!$city) {
            return [];
        }

        return [
            'postCode' => $city->getPostCode(),
            'name'     => $city->getName(),
            'country'  => $city->getCountry(),
            'province' => $city->getProvince(),
            'code'     => $city->getCode(),
        ];
    }

    /**
     * Transforms a data array into a City instance.
     *
     * @param $data
     *
     * @return Address
     *
     * @throws TransformationFailedException
     */
    public function reverseTransform($data)
    {
        $this->validateData($data);

        $city = new City($data['name'], $data['postCode']);

        if (isset($data['code'])) {
            $city->setCode($data['code']);
        }

        if (isset($data['country'])) {
            $city->setCountry($countryRepository->get($data['country']->getId()));
        }

        if (isset($data['province'])) {
            $city->setProvince($provinceRepository->get($data['province']->getId()));
        }

        return $city;
    }

    /**
     * Validate data array.
     *
     * @param array $data
     */
    private function validateData(array $data)
    {
        $mandatoryProperties = ['name', 'postCode'];

        foreach ($mandatoryProperties as $property) {
            if (!isset($data[$property]) || $data[$property] == null) {
                throw new TransformationFailedException(sprintf(
                    'Property %s is mandatory to construct a City',
                    $property
                ));
            }
        }
    }
}
