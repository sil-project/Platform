<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\UIBuilder\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Blast\Component\UIBuilder\Builder\UIBuilder;
use Blast\Component\UIBuilder\Builder\GroupBuilder;
use Blast\Component\UIBuilder\Factory\BaseAbstractFactory;

class BuilderTest extends TestCase
{
    public function setUp()
    {
        $this->markTestSkipped('disabled');
    }

    public function notestBuilder()
    {
        $view = new UIBuilder(new BaseAbstractFactory());
        $view
        ->form('category')
          ->tabContainer()
            ->tab('general')
              ->group('')

                ->field('name')
                  ->required()
                ->endField()

                ->field('treeParent')
                  ->type(NestedTreeableType::class)
                  ->model(Category::class)
                  ->required(false)
                  ->placeholder('')
                ->endField()

              ->endGroup()
            ->endTab()
          ->endTabContainer()
       ->endForm();
    }

    public function notestBuilder2()
    {
        $view = new UIBuilder(new BaseAbstractFactory());
        $view

          ->tab('general')
            ->template('@MyBundle:Contact/Info.html.twig')
          ->end()

          ->tab('organizations')
            ->import('view.organizations')
          ->end()

          ->tab('coordonnées')

            ->group('adresses')
              ->css(['col-md-6'])
              ->template('@MyBundle:Address/CardView.html.twig')
              ->data()
                ->type('collection')
                ->model('My\Address')
                ->path('addresses')
              ->end()
            ->end()

            ->group('téléphones')
              ->css(['col-md-6'])
              ->template('@MyBundle:Phone/CardView.html.twig')
              ->data()
                ->type('collection')
                ->model('My\Phone')
                ->repository()
                  ->method('findPhonesByContact')
                  ->arguments(['&'])
                ->end()
              ->end()
            ->end()

          ->end()

        ->end();
    }

    public function testBuilder()
    {
        $view = new UIBuilder(new BaseAbstractFactory(), 'my.view');
        $view->tabContainer('my.tab_container')
          ->tab('my.tab')
            ->group('my.group.1')
              ->form('my.form')
                ->inputField('my.field')
                  ->label('my.field.label')
                  ->type('integer')
                  ->required()
                ->end()
              ->end()
            ->end()
            ->group('my.group.2')
              ->useBuilder($this->createGroupBuilder())
            ->end()
          ->end()
        ->end();

        $view
          ->override('my.form')
            ->override('my.field')
              ->label('my.field2.label')
              ->type('string')
            ->end()
          ->end();

        print_r($view->getModel());
    }

    public function createGroupBuilder()
    {
        $group = new GroupBuilder(null, new BaseAbstractFactory(), 'dummy.group');
        $group
        ->field('my.other.field')
        ->end();

        return $group;
    }
}
