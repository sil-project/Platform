<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\CoreBundle\Twig;

abstract class DebugTemplate extends \Twig_Template
{
    public function display(array $context, array $blocks = array())
    {
        echo "\n" . '<!-- START: ' . $this->getTemplateName() . ' -->' . "\n";
        parent::display($context, $blocks);
        echo "\n" . '<!-- END: ' . $this->getTemplateName() . ' -->';
    }
}
