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
use Sil\Component\Contact\Repository\ProvinceRepositoryInterface;
use Sil\Bundle\ContactBundle\Entity\Province;

/**
 * Name to Province transformer.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class NameToProvinceTransformer implements DataTransformerInterface
{
    /**
     * Province repository.
     *
     * @var ProvinceRepositoryInterface
     */
    private $repository;

    /**
     * Transforms a Province name into a Province instance.
     *
     * @param ProvinceRepositoryInterface $repository
     */
    public function __construct(ProvinceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param  $name
     *
     * @return Province
     */
    public function transform($name)
    {
        return $this->repository->findOneBy(['name' => $name]);
    }

    /**
     * @param $province
     *
     * @return string
     *
     * @throws TransformationFailedException
     */
    public function reverseTransform($province)
    {
        if (!$province) {
            return null;
        }

        return $province->getName();
    }
}
