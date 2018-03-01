<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Invoice\Model;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
interface CustomerInfoInterface
{
    /**
     * Get the value of name.
     *
     * @return string
     */
    public function getName(): string;
}
