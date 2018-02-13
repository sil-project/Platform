<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\Code\Generator;

use Blast\Component\Code\Model\CodeInterface;

interface CodeGeneratorInterface
{
    /**
     * Validate code with its own validation format.
     *
     * @param CodeInterface $code The code to validate
     *
     * @return bool True if valid, false if not valid
     */
    public function isValid(CodeInterface $code): bool;

    /**
     * Check if generated code is unique.
     *
     * @param CodeInterface $code
     *
     * @return bool
     */
    public function isUnique(CodeInterface $code): bool;
}
