<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PlatformBundle\Entity\SilUserBundle;

use Blast\Component\Grid\Model\CustomReportInterface;
use Blast\Component\Grid\Model\CustomReportOwnerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sil\Bundle\UserBundle\Entity\User as BaseUser;

class User extends BaseUser implements CustomReportOwnerInterface
{
    /**
     * User's custom reports.
     *
     * @var Collection
     */
    protected $customReports;

    public function __construct()
    {
        parent::__construct();

        $this->customReports = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomReports(?string $gridName): array
    {
        if ($gridName) {
            return $this->customReports->filter(function (CustomReportInterface $report) use ($gridName) {
                return $report->getGridName() === $gridName;
            })->getValues();
        }

        return $this->customReports->getValues();
    }
}
