<?php

declare(strict_types=1);

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Domain\Entity;

use Blast\BaseEntitiesBundle\Entity\Traits\Guidable;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class Partner implements PartnerInterface
{
    use Guidable;

    private $fulltextName;

    public function getFulltextName()
    {
        return $this->fulltextName;
    }

    public function setFulltextName($fulltextName)
    {
        $this->fulltextName = $fulltextName;
    }
}
