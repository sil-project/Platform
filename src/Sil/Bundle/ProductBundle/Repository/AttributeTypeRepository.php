<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ProductBundle\Repository;

use Blast\Bundle\ResourceBundle\Doctrine\ORM\Repository\ResourceRepository;
use Sil\Component\Product\Repository\AttributeTypeRepositoryInterface;
use Sil\Component\Product\Model\AttributeType;
use Doctrine\Common\Collections\Collection;

class AttributeTypeRepository extends ResourceRepository implements AttributeTypeRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findAttributeTypeByName(string $name): ?AttributeType
    {
        return $this->findOneBy(['name' => $name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeTypesForType(string $type): Collection
    {
        return $this->findBy(['type' => $type]);
    }
}
