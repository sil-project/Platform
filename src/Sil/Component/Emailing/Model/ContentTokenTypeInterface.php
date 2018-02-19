<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Emailing\Model;

interface ContentTokenTypeInterface
{
    /**
     * Gets the token type name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Gets the data type of the token.
     *
     * @return ContentTokenDataType
     */
    public function getDataType(): ContentTokenDataType;
}
