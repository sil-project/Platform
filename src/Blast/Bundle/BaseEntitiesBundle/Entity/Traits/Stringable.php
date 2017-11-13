<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Entity\Traits;

use Symfony\Component\Intl\Locale;

trait Stringable
{
    // @TODO: Set method name configurable
    public function __toString(): string
    {
        /* @todo : remove this */
        /* BugFix for sylius TranslatableTrait (example for shipping method $this->getTranslation()->getName(); ) */
        /*
           if (property_exists(get_class($this), 'currentLocale') && method_exists(get_class($this), 'setCurrentLocale')) {
           dump($this->currentLocale);
           if (!$this->currentLocale) {
           $this->setCurrentLocale(Locale::getDefault());
           }
           }
        */

        if (method_exists(get_class($this), 'getName')) {
            return (string) $this->getName();
        }
        if (method_exists(get_class($this), 'getCode')) {
            return (string) $this->getCode();
        }
        if (method_exists(get_class($this), 'getId')) {
            return (string) $this->getId();
        }

        return '';
    }
}
