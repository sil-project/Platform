<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Uom\Tests\Unit\InMemoryRepository;

use Sil\Component\Uom\Repository\UomTypeRepositoryInterface;
use Sil\Component\Uom\Model\UomType;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class UomTypeRepository extends InMemoryRepository implements UomTypeRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(UomType::class);
    }
}
