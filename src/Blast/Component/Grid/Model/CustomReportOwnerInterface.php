<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\Grid\Model;

interface CustomReportOwnerInterface
{
    /**
     * Gets the User's custom reports.
     *
     * @param string|null $gridName If null, gets all reports, if set, get only for the grid
     *
     * @return array|CustomReportInterface[]
     */
    public function getCustomReports(?string $gridName): array;
}
