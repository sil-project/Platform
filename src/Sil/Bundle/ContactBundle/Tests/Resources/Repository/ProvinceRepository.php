<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Tests\Resources\Repository;

use Blast\Component\Resource\Repository\InMemoryRepository;
use Sil\Component\Contact\Repository\ProvinceRepositoryInterface;

/**
 * ProvinceRepository.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class ProvinceRepository extends InMemoryRepository implements ProvinceRepositoryInterface
{
}