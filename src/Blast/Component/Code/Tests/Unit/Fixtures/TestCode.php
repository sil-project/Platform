<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\Code\Tests\Unit\Fixtures;

use Blast\Component\Code\Model\AbstractCode;
use Blast\Component\Code\Model\CodeInterface;

class TestCode extends AbstractCode implements CodeInterface
{
    public function __construct($value, $format = '/^[A-Z]{3}\-[\d]{8}$/')
    {
        $this->format = $format;
        $this->value = $value;
    }
}
