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
use Blast\Component\Resource\Model\ResourceInterface;

interface CodeAwareRepositoryInterface
{
    /**
     * Find a resource by its Code.
     *
     * @param string $codeValue
     *
     * @return ResourceInterface
     */
    public function findByCode(CodeInterface $codeValue): ?ResourceInterface;

    /**
     * Find a resource by its Code value.
     *
     * @param string $codeValue
     *
     * @return ResourceInterface
     */
    public function findByCodeValue(string $codeValue): ?ResourceInterface;
}
