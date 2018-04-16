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
use Sil\Component\Contact\Model\AddressInterface;
use Sil\Bundle\ContactBundle\Entity\City;
use Sil\Component\Contact\Repository\ContactRepositoryInterface;

/**
 * Array to Address transformer.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class ArrayToAddressTransformer implements DataTransformerInterface
{
    /**
     * Contact repository.
     *
     * @var ContactRepositoryInterface
     */
    private $contactRepository;

    /**
     * @param ContactRepositoryInterface $repository
     */
    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * Transforms an Address instance into an array.
     *
     * @param  $address
     *
     * @return array
     */
    public function transform($address)
    {
        return $address;
    }

    /**
     * Transforms a data array into an Address instance.
     *
     * @param $data
     *
     * @return Address
     *
     * @throws TransformationFailedException
     */
    public function reverseTransform($data)
    {
        if ($data instanceof AddressInterface) {
            return $data;
        }

        $this->validateData($data);

        $address = new Address(
            $data['street'],
            $data['city'],
            $data['country']
        );

        if (isset($data['province'])) {
            $address->setProvince($data['province']);
        }

        if (isset($data['type'])) {
            $address->setType($data['type']);
        }

        if (isset($data['contact'])) {
            $contact = $this->contactRepository->get($data['contact']);

            $contact->addAddress($address);
            $address->setContact($contact);
        }

        return $address;
    }

    /**
     * Validate data array.
     *
     * @param array $data
     */
    private function validateData(array $data)
    {
        $mandatoryProperties = ['street', 'city', 'country'];

        foreach ($mandatoryProperties as $property) {
            if (!isset($data[$property]) || $data[$property] == null) {
                throw new TransformationFailedException(sprintf(
                    'Property %s is mandatory to construct an Address',
                    $property
                ));
            }
        }
    }
}
