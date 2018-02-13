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

use Blast\Component\Code\Generator\AbstractCodeGenerator;
use Blast\Component\Code\Model\CodeInterface;
use Sil\Component\Product\Model\ProductCodeInterface;
use Sil\Component\Product\Model\ProductVariantCode;

class ProductVariantCodeGenerator extends AbstractCodeGenerator implements ProductVariantCodeGeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate(ProductCodeInterface $productCode, array $productOptions): CodeInterface
    {
        $optionsCodePart = '';

        $firstIteration = true;

        foreach ($productOptions as $option) {
            if (!$firstIteration) {
                $optionsCodePart .= '-';
            } else {
                $firstIteration = false;
            }

            $optionsCodePart .= strtoupper($option->getValue());
        }

        $code = new ProductVariantCode(sprintf(
            '%s-%s',
            $productCode->getValue(),
            $optionsCodePart
        ));

        if (!$this->isValid($code)) {
            throw new DomainException(sprintf('The generated code %s does not fit the regex format %s', $code, $code->getFormat()));
        }

        if (!$this->isUnique($code)) {
            throw new DomainException(sprintf('The generated code for prefix %s and date %s is not unique', $prefix, $date->format('Ymd')));
        }

        return $code;
    }
}
