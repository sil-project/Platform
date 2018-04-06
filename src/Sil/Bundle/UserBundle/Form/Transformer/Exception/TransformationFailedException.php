<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\UserBundle\Form\Transformer\Exception;

use Symfony\Component\Form\Exception\TransformationFailedException as BaseTransformationFailedException;

/* @todo: factoryze this with other bundle (like product) */
class TransformationFailedException extends BaseTransformationFailedException
{
    public function __construct(string $valueClass, array $value, array $mandatoryFields)
    {
        $this->message = sprintf(
            'Cannot transform data to object of class « %s ». Mandatory fields are : « %s ». Given fields are : « %s »',
            $valueClass,
            implode(' », « ', $mandatoryFields),
            implode(' », « ', $value)
        );
    }
}
