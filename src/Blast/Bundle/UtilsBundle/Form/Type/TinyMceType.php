<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UtilsBundle\Form\Type;

use Blast\Bundle\CoreBundle\Form\AbstractType as BaseAbstractType;

class TinyMceType extends BaseAbstractType
{
    public function getParent()
    {
        return 'textarea';
    }

    public function getBlockPrefix()
    {
        return 'blast_tinymce';
    }
}
