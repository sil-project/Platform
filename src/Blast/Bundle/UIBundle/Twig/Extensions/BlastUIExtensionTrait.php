<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UIBundle\Twig\Extensions;

use InvalidArgumentException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

trait BlastUIExtensionTrait
{
    /**
     * @var string
     */
    private $extensionPrefix = 'blast_';

    /**
     * @var array
     */
    private $blastUiParameter;

    /**
     * @param array $blastUiParameter
     */
    public function __construct(array $blastUiParameter)
    {
        //convert array to stdClass using json_decode(json_encode(...))
        $this->blastUiParameter = json_decode(json_encode($blastUiParameter));
    }

    /**
     * Register function helper.
     *
     * @param string $functionName
     * @param string $method
     * @param bool   $needsEnv
     * @param array  $safeFormats
     *
     * @return Twig_SimpleFunction
     */
    private function registerFunction(
        string $functionName,
        string $method,
        ?bool $needsEnv = false,
        ?array $safeFormats = ['html']
    ): \Twig_SimpleFunction {
        $options = [
            'is_safe'           => $safeFormats,
            'needs_environment' => $needsEnv,
        ];

        return new \Twig_SimpleFunction(
            $this->extensionPrefix . $functionName,
            [$this, $method],
            $options
        );
    }

    /**
     * Returns the default template by its name.
     *
     * @param string $name
     *
     * @return string
     */
    private function getTemplate(string $name): string
    {
        $templateVar = null;
        $templateKey = 'templates.' . $name;
        $accessor = new PropertyAccessor();

        if (!$accessor->isReadable($this->blastUiParameter, $templateKey)) {
            throw new InvalidArgumentException(
                  sprintf(
                      'Template with name « %s » does not exists in configuration. Available templates are : « %s »',
                      $name, implode(' » , « ', array_keys(get_object_vars($this->blastUiParameter->templates)))
                  )
              );
        }

        return $accessor->getValue($this->blastUiParameter, $templateKey);
    }
}
