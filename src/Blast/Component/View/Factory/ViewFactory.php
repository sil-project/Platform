<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\View\Factory;

use Blast\Component\View\Exception\UnexpectedTypeException;
use Blast\Component\View\Registry\ViewTypeRegistryInterface;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class ViewFactory implements ViewFactoryInterface
{
    private const DEFAULT_VIEW_TYPE = 'Blast\Component\View\Type\ViewType';
    private const DEFAULT_FIELD_TYPE = 'Blast\Component\View\Type\TextType';
    /**
     * @var ViewTypeRegistryInterface
     */
    private $registry;

    public function __construct(ViewTypeRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function createBuilder($type = self::DEFAULT_VIEW_TYPE, $data = null, array $options = array())
    {
        if (!is_string($type)) {
            throw new UnexpectedTypeException($type, 'string');
        }

        return $this->createNamedBuilder($this->registry->getType($type)->getBlockPrefix(), $type, $data, $options);
    }

    public function createNamedBuilder($name, $type = self::DEFAULT_VIEW_TYPE, $data = null, array $options = array())
    {
        if (null !== $data && !array_key_exists('data', $options)) {
            $options['data'] = $data;
        }
        if (!is_string($type)) {
            throw new UnexpectedTypeException($type, 'string');
        }
        $type = $this->registry->getType($type);
        $builder = $type->createBuilder($this, $name, $options);
        $type->buildView($builder, $builder->getOptions());

        return $builder;
    }
}
