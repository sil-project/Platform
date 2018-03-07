<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\Code\Tests\Unit\Fixtures;

use Blast\Component\Code\Model\CodeInterface;
use Blast\Component\Resource\Model\ResourceInterface;

class TestEntity implements ResourceInterface
{
    /**
     * @var CodeInterface
     */
    protected $code;

    public function __construct(CodeInterface $code)
    {
        $this->code = $code;
    }

    /**
     * @return CodeInterface
     */
    public function getCode(): CodeInterface
    {
        return $this->code;
    }
}
