<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Product\Generator;

use Blast\Component\Code\Model\CodeInterface;
use Blast\Component\Code\Generator\CodeGeneratorInterface;

interface ProductCodeGeneratorInterface extends CodeGeneratorInterface
{
    public function generate(string $productName): CodeInterface;
}
