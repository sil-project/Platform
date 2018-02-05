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

interface CodeGeneratorInterface
{
    /**
     * Validate code with its own validation format.
     *
     * @param CodeInterface $code The code to validate
     *
     * @return bool True if valid, false if not valid
     */
    public function validate(CodeInterface $code): bool;
}
