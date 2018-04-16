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
use Sil\Bundle\ContactBundle\Entity\Phone;
use Sil\Component\Contact\Repository\ContactRepositoryInterface;

/**
 * Array to Phone transformer.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class ArrayToPhoneTransformer implements DataTransformerInterface
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
     * Transforms a Phone instance into an array.
     *
     * @param  $phone
     *
     * @return array
     */
    public function transform($phone)
    {
        return $phone;
    }

    /**
     * Transforms a data array into a Phone instance.
     *
     * @param $data
     *
     * @return Phone
     *
     * @throws TransformationFailedException
     */
    public function reverseTransform($data)
    {
        if ($data instanceof Phone) {
            return $data;
        }

        if (!isset($data['number'])) {
            throw new TransformationFailedException('Property number is mandatory to construct a Phone');
        }

        $phone = new Phone($data['number']);

        if (isset($data['type'])) {
            $phone->setType($data['type']);
        }

        if (isset($data['contact'])) {
            $contact = $this->contactRepository->get($data['contact']);

            $contact->addPhone($phone);
            $phone->setContact($contact);
        }

        return $phone;
    }
}
