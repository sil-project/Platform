<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\Code\Repository;

use Blast\Component\Code\Model\CodeInterface;

interface CodeAwareRepositoryInterface
{
    /**
     * Find a Code by a code value.
     *
     * @param string $codeValue
     *
     * @return CodeInterface
     */
    public function findCodeByValue(string $codeValue): ?CodeInterface;
}
