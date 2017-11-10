<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Sil\Bundle\EcommerceBundle\Entity\SalesJournalItem;

class SalesJournalItemRepository extends EntityRepository
{
    public function add(SalesJournalItem $item, $autoflush = true)
    {
        $this->_em->persist($item);
        if ($autoflush) {
            $this->_em->flush();
        }
    }
}
