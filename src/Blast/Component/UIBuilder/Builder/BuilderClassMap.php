<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Builder;

use Blast\Component\UIBuilder\Model\UiModelInterface;

class BuilderClassMap
{
    public static function getBuilderClassFromModel(UiModelInterface $model): string
    {
        $modelClass = (new \ReflectionClass($model))->getShortName();
        $builderClass = __NAMESPACE__ . '\\' . $modelClass . 'Builder';

        return $builderClass;
    }
}
