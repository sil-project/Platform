<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UIBundle\Twig\Extensions;

use Symfony\Component\PropertyAccess\PropertyAccessor;

class ParametersExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $blastUiParameter;

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'parameter',
                [$this, 'getParameter'],
                [
                    'is_safe' => ['html'],
                ]
            ),
        ];
    }

    public function getParameter(string $parameterName)
    {
        $propertyAccessor = new PropertyAccessor(PropertyAccessor::ACCESS_TYPE_PROPERTY);

        return $propertyAccessor->getValue($this->blastUiParameter, $parameterName);
    }

    /**
     * @param array $blastUiParameter
     */
    public function setBlastUiParameter(array $blastUiParameter): void
    {
        $this->blastUiParameter = json_decode(json_encode($blastUiParameter));
    }
}
