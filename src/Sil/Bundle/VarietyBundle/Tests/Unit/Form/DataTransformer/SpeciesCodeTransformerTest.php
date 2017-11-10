<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\VarietyBundle\Form\DataTransformer\Test\Unit;

use PHPUnit\Framework\TestCase;
use Sil\Bundle\VarietyBundle\Form\DataTransformer\SpeciesCodeTransformer;

class SpeciesCodeTransformerTest extends TestCase
{
    /**
     * @var SpeciesCodeTransformer
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new SpeciesCodeTransformer();
    }

    protected function tearDown()
    {
    }

    /**
     * @covers \Sil\Bundle\VarietyBundle\Form\DataTransformer\SpeciesCodeTransformer::transform
     */
    public function testTransform()
    {
        $test = $this->object->transform('test');
        $this->assertEquals($test, 'test');
    }

    /**
     * @covers \Sil\Bundle\VarietyBundle\Form\DataTransformer\SpeciesCodeTransformer::reverseTransform
     */
    public function testReverseTransform()
    {
        $test = strtoupper(trim('TeSt'));
        $reverse = $this->object->reverseTransform('TeSt');
        $this->assertEquals($test, $reverse);
    }
}
