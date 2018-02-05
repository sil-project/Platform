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

use Sil\Component\Product\Model\CodeInterface;
use Sil\Component\Product\Model\ProductCode;

class ProductCodeGenerator extends AbstractCodeGenerator implements ProductCodeGeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate(string $productName): CodeInterface
    {
        return new ProductCode(substr(strtoupper($productName), 0, 7));
    }
}
