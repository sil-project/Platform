<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Repository;

use Blast\Bundle\ResourceBundle\Doctrine\ORM\Repository\NestedTreeResourceRepository;
use Sil\Component\Contact\Repository\GroupRepositoryInterface;

/**
 * Group repository.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class GroupRepository extends NestedTreeResourceRepository implements GroupRepositoryInterface
{
}
