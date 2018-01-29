<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ManufacturingBundle\Doctrine\ORM;

use Sil\Bundle\ManufacturingBundle\Domain\Repository\ManufacturingOrderRepositoryInterface;
use Blast\Bundle\ResourceBundle\Doctrine\ORM\Repository\ResourceRepository;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class ManufacturingOrderRepository extends ResourceRepository implements ManufacturingOrderRepositoryInterface
{
    //put your code here
}
