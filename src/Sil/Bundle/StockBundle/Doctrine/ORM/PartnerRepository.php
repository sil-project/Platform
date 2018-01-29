<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Doctrine\ORM;

use Sil\Component\Stock\Repository\PartnerRepositoryInterface;
use Blast\Bundle\ResourceBundle\Doctrine\ORM\Repository\ResourceRepository;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class PartnerRepository extends ResourceRepository implements PartnerRepositoryInterface
{
    public function getCustomers(): array
    {
        return [];
    }

    public function getSuppliers(): array
    {
        return [];
    }
}
