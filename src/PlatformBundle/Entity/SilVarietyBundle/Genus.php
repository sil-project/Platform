<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PlatformBundle\Entity\SilVarietyBundle;

use Sil\Bundle\VarietyBundle\Entity\Genus as BaseGenus;
use Sil\Bundle\SonataSyliusUserBundle\Entity\Traits\Traceable;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class Genus extends BaseGenus
{
    use Traceable;
}
