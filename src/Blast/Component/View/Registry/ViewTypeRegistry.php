<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\View\Registry;

use InvalidArgumentException;
use Blast\Component\View\ResolvedType\ResolvedViewTypeInterface;
use Blast\Component\View\ResolvedType\ResolvedViewType;
use Blast\Component\View\Type\ViewTypeInterface;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class ViewTypeRegistry implements ViewTypeRegistryInterface
{
    /**
     * @var ViewTypeInterface[]
     */
    private $types = array();

    public function __construct()
    {
    }

    public function getType($name): ViewTypeInterface
    {
        if (!isset($this->types[$name])) {
            $type = null;

            // Support fully-qualified class names
            if (!class_exists($name)) {
                throw new InvalidArgumentException(sprintf('Could not load type "%s": class does not exist.', $name));
            }
            if (!is_subclass_of($name, 'Blast\Component\View\ViewTypeInterface')) {
                throw new InvalidArgumentException(sprintf('Could not load type "%s": class does not implement "Blast\Component\View\ViewTypeInterface".', $name));
            }

            $type = new $name();

            $this->types[$name] = $this->resolveType($type);
        }

        return $this->types[$name];
    }

    /**
     * Wraps a type into a ResolvedFormTypeInterface implementation and connects
     * it with its parent type.
     *
     * @param FormTypeInterface $type The type to resolve
     *
     * @return ResolvedFormTypeInterface The resolved type
     */
    private function resolveType(ViewTypeInterface $type)
    {
        $parentType = $type->getParent();
        $fqcn = get_class($type);

        return $this->createResolvedType(
            $type, $parentType ? $this->getType($parentType) : null
        );
    }

    public function createResolvedType(ViewTypeInterface $type, ResolvedViewTypeInterface $parent = null)
    {
        return new ResolvedViewType($type, $parent);
    }
}
