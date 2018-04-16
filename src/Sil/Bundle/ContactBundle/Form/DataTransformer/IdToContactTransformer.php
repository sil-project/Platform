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
use Sil\Bundle\ContactBundle\Entity\Contact;
use Sil\Component\Contact\Repository\ContactRepositoryInterface;

/**
 * Id to Contact transformer.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class IdToContactTransformer implements DataTransformerInterface
{
    /**
     * Contact repository.
     *
     * @var ContactRepositoryInterface
     */
    private $repository;

    /**
     * @param ContactRepositoryInterface $repository
     */
    public function __construct(ContactRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param  $contact
     *
     * @return array
     */
    public function transform($contact)
    {
        return $contact;
    }

    /**
     * @param $id
     *
     * @return Contact
     *
     * @throws TransformationFailedException
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        return $this->repository->get($id);
    }
}
