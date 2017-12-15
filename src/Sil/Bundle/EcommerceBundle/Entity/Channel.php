<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Entity;

use Sylius\Component\Core\Model\Channel as BaseChannel;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Stringable;

/**
 * @author Romain SANCHEZ <romain.sanchez@libre-informatique.fr>
 */
class Channel extends BaseChannel
{
    use Stringable;
}
