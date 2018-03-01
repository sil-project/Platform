<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Manufacturing\Generator;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class BomCodeGenerator implements BomCodeGeneratorInterface
{
    /**
     * @return string
     */
    public function generate(): string
    {
        return strtoupper(substr(md5((string) mt_rand()), 0, 7));
    }
}
