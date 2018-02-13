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
use Blast\Component\Code\Repository\CodeAwareRepositoryInterface;

class AbstractCodeGenerator implements CodeGeneratorInterface
{
    /**
     * The repository that can fetch if generated code already exists.
     *
     * @var CodeAwareRepositoryInterface
     */
    protected $codeAwareRepository;

    /**
     * {@inheritdoc}
     */
    public function isValid(CodeInterface $code): bool
    {
        $valid = true;

        if (preg_match($code->getFormat(), $code->getValue()) === 0) {
            $valid = false;
        }

        return $valid;
    }

    /**
     * {@inheritdoc}
     */
    public function isUnique(CodeInterface $code): bool
    {
        $unique = true;

        if ($this->codeAwareRepository !== null) {
            $unique = $this->codeAwareRepository->findCodeByValue($code->getValue()) === null;
        }

        return $unique;
    }

    /**
     * @param CodeAwareRepositoryInterface $codeAwareRepository
     */
    public function setCodeAwareRepository(CodeAwareRepositoryInterface $codeAwareRepository): void
    {
        $this->codeAwareRepository = $codeAwareRepository;
    }
}
