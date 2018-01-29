<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Stock\Generator;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class OperationCodeGenerator implements OperationCodeGeneratorInterface
{
    /**
     * @return string
     */
    public function generate(): string
    {
        return strtoupper(substr(md5((string) mt_rand()), 0, 7));
    }
}
