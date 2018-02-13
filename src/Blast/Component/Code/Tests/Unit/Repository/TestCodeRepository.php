<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\Code\Tests\Unit\Repository;

use Blast\Component\Resource\Repository\InMemoryRepository;
use Blast\Component\Code\Model\CodeInterface;
use Blast\Component\Code\Repository\CodeAwareRepositoryInterface;

class TestCodeRepository extends InMemoryRepository implements CodeAwareRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findCodeByValue(string $codeValue): ?CodeInterface
    {
        return $this->findOneBy(['value' => $codeValue]);
    }
}
