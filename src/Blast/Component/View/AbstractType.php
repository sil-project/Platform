<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\View;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Util\StringUtil;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
abstract class AbstractType implements ViewTypeInterface
{
    public function buildView(ViewBuilderInterface $builder, array $options)
    {
        $builder->setCondition($options['condition']);
    }

    public function buildRenderingView(RenderingView $rview, ViewInterface $view, array $options)
    {
    }

    public function finishRenderingView(RenderingView $rview, ViewInterface $view, array $options)
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('condition', null);
    }

    public function getBlockPrefix(): string
    {
        return StringUtil::fqcnToBlockPrefix(get_class($this));
    }

    public function onPreSetData(ViewInterface $view, $data)
    {
    }

    public function onPostSetData(ViewInterface $view, $data)
    {
    }

    public function getParent()
    {
        return 'Blast\Component\View\Type\ViewType';
    }
}
