<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\AccountBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Sil\Bundle\AccountBundle\Entity\Account;

/**
 * Array to Account transformer.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class ArrayToAccountTransformer implements DataTransformerInterface
{
    /**
     * Transforms an Account instance into an array.
     *
     * @param  $account
     *
     * @return array
     */
    public function transform($account)
    {
        return $account;
    }

    /**
     * Transforms a data array into an Account instance.
     *
     * @param $data
     *
     * @return Account
     *
     * @throws TransformationFailedException
     */
    public function reverseTransform($data)
    {
        if ($data instanceof Account) {
            return $data;
        }

        if (!isset($data['name'])) {
            throw new TransformationFailedException('Property name is mandatory to construct an Account');
        }

        if (!isset($data['code'])) {
            throw new TransformationFailedException('Property code is mandatory to construct an Account');
        }

        $account = new Account($data['name'], $data['code']);

        return $account;
    }
}
