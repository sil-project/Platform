<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\User\Repository;

use Blast\Component\Resource\Repository\ResourceRepositoryInterface;
use Sil\Component\User\Model\RoleInterface;

interface RoleRepositoryInterface extends ResourceRepositoryInterface
{
    /**
     * Retrieve exhaustive role hierarchy
     *
     * @return array
     */
    public function getRoleHierarchy(): array;
}
