<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Entity\Traits;

/**
 * Classes that have the loggable trait will be versioned in database.
 * Another way to do that is to declare blast:loggable :true in the entity yaml orm mapping file.
 */
trait Loggable
{
}
