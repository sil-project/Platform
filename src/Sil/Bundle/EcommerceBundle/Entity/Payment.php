<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Entity;

use Sylius\Component\Core\Model\Payment as BasePayement;

class Payment extends BasePayement
{
    public function __toString()
    {
        $str = '';
        if ($this->getMethod() !== null) {
            $str .= $this->getMethod()->getCode() . '-';
        }
        if ($this->getAmount() !== null) {
            $str .= $this->getAmount();
        }

        return $str;
    }
}
