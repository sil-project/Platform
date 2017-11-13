<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\VarietiesBundle\Entity;

/**
 * VarietyDescriptionPlant.
 */
class VarietyDescriptionPlant extends VarietyDescription
{
    public function getFieldset()
    {
        return 'plant';
    }
}
