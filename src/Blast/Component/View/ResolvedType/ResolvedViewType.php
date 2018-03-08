<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\View\ResolvedType;

use Blast\Component\View\Factory\ViewFactoryInterface;
use Blast\Component\View\Builder\ViewBuilder;
use Blast\Component\View\Builder\ViewBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Blast\Component\View\Type\ViewTypeInterface;


/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class ResolvedViewType implements ResolvedViewTypeInterface
{
    /**
     * @var ViewTypeInterface
     */
    private $innerType;

    /**
     * @var ResolvedViewTypeInterface|null
     */
    private $parent;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    public function __construct(ViewTypeInterface $innerType, ResolvedViewTypeInterface $parent = null)
    {
        $this->innerType = $innerType;
        $this->parent = $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return $this->innerType->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getInnerType()
    {
        return $this->innerType;
    }

    public function createBuilder(ViewFactoryInterface $factory, $name, array $options = array())
    {
        $options = $this->getOptionsResolver()->resolve($options);
        $dataClass = isset($options['data_class']) ? $options['data_class'] : null;
        $builder = new ViewBuilder($name, $dataClass, $factory, $options);
        $builder->setType($this);

        return $builder;
    }

    public function createRenderingView(ViewInterface $view, RenderingView $parent = null)
    {
        return new RenderingView($parent);
    }

    public function buildView(ViewBuilderInterface $builder, array $options)
    {
        if (null !== $this->parent) {
            $this->parent->buildView($builder, $options);
        }

        $this->innerType->buildView($builder, $options);
    }

    public function buildRenderingView(RenderingView $rview, ViewInterface $view, array $options)
    {
        if (null !== $this->parent) {
            $this->parent->buildRenderingView($rview, $view, $options);
        }

        $this->innerType->buildRenderingView($rview, $view, $options);
    }

    public function finishRenderingView(RenderingView $rview, ViewInterface $view, array $options)
    {
        if (null !== $this->parent) {
            $this->parent->finishRenderingView($rview, $view, $options);
        }

        $this->innerType->finishRenderingView($rview, $view, $options);
    }

    public function onPreSetData(ViewInterface $view, $data)
    {
        if (null !== $this->parent) {
            $this->parent->onPreSetData($view, $data);
        }
        $this->innerType->onPreSetData($view, $data);
    }

    public function onPostSetData(ViewInterface $view, $data)
    {
        if (null !== $this->parent) {
            $this->parent->onPostSetData($view, $data);
        }
        $this->innerType->onPostSetData($view, $data);
    }

    public function getOptionsResolver()
    {
        if (null === $this->optionsResolver) {
            if (null !== $this->parent) {
                $this->optionsResolver = clone $this->parent->getOptionsResolver();
            } else {
                $this->optionsResolver = new OptionsResolver();
            }

            $this->innerType->configureOptions($this->optionsResolver);
        }

        return $this->optionsResolver;
    }
}
