<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Product\Repository;

use Blast\Component\Resource\Repository\ResourceRepositoryInterface;
use Sil\Component\Product\Model\AttributeType;
use Doctrine\Common\Collections\Collection;

interface AttributeTypeRepositoryInterface extends ResourceRepositoryInterface
{
    /**
     * Retreive attribute type by its name.
     *
     * @param string $name The name of the attribute type
     *
     * @return AttributeType|null
     */
    public function findAttributeTypeByName(string $name): ?AttributeType;

    /**
     * Retreive attributes types for specific data type (AttributeType::TYPE_*).
     *
     * @param string $type The targeted type. Must be a type of AttributeType::TYPE_* constants
     *
     * @return Collection The collection of unused attribute types
     */
    public function getAttributeTypesForType(string $type): Collection;
}
