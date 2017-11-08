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

namespace AppBundle\Entity\SilVarietyBundle;

use Sil\Bundle\VarietyBundle\Entity\Family as BaseFamily;
use Sil\Bundle\SonataSyliusUserBundle\Entity\Traits\Traceable;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class Family extends BaseFamily
{
    use Traceable;
}
