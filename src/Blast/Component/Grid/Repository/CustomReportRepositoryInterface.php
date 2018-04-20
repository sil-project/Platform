<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\Grid\Repository;

use Blast\Component\Resource\Repository\ResourceRepositoryInterface;
use Blast\Component\Grid\Model\CustomReportOwnerInterface;

interface CustomReportRepositoryInterface extends ResourceRepositoryInterface
{
    /**
     * Gets all custom reports (public and owned) for given grid name and owner.
     *
     * @param string                     $gridName
     * @param CustomReportOwnerInterface $owner
     *
     * @return array
     */
    public function getCustomReportsForGridNameAndOwner(string $gridName, CustomReportOwnerInterface $owner): array;
}
