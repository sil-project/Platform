<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\Code\Model;

interface CodeInterface
{
    /**
     * Gets the code value.
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Gets the code format.
     *
     * @return string
     */
    public function getFormat(): string;
}
