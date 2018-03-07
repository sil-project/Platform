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

use Blast\Component\Code\Model\CodeInterface;
use Blast\Component\Code\Repository\CodeAwareRepositoryInterface;
use Blast\Component\Resource\Model\ResourceInterface;
use Blast\Component\Resource\Repository\InMemoryRepository;

class TestEntityRepository extends InMemoryRepository implements CodeAwareRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findByCodeValue(string $codeValue): ?ResourceInterface
    {
        return $this->findOneBy(['code.value' => $codeValue]);
    }

    /**
     * {@inheritdoc}
     */
    public function findByCode(CodeInterface $code): ?ResourceInterface
    {
        return $this->findOneBy(['code' => $code]);
    }
}
