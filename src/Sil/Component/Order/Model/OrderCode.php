<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Order\Model;

use Blast\Component\Code\Model\AbstractCode;

class OrderCode extends AbstractCode implements OrderCodeInterface
{
    public function __construct($value, $format = '/^(FA)([0-9]{8})$/')
    {
        $this->format = $format;
        $this->value = $value;
    }
}
