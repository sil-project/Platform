<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ProductBundle\Generator;

use DomainException;
use Blast\Component\Code\Generator\AbstractCodeGenerator;
use Blast\Component\Code\Model\CodeInterface;
use Sil\Component\Product\Generator\ProductCodeGeneratorInterface;
use Sil\Bundle\ProductBundle\Entity\ProductCode;

class ProductCodeGenerator extends AbstractCodeGenerator implements ProductCodeGeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate(string $productName): CodeInterface
    {
        $code = new ProductCode(strtoupper(substr(md5((string) mt_rand()), 0, 8)));

        if (!$this->isValid($code)) {
            throw new DomainException(sprintf('The generated code %s does not fit the regex format %s', $code, $code->getFormat()));
        }

        if (!$this->isUnique($code)) {
            throw new DomainException(sprintf('The generated code %s is not unique', $code));
        }

        return $code;
    }
}
