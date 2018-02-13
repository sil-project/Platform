<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Product\Model;

use Blast\Component\Resource\Model\ResourceInterface;
use Blast\Component\Code\Model\AbstractCode;

class ProductCode extends AbstractCode implements ProductCodeInterface, ResourceInterface
{
    public function __construct($value, $format = '/^[A-Z0-9]{1,8}$/')
    {
        parent::__construct($value, $format);
    }
}
