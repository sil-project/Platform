<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\VarietiesBundle\Form\DataTransformer\Test\Unit;

use PHPUnit\Framework\TestCase;
use Librinfo\VarietiesBundle\Form\DataTransformer\SpeciesCodeTransformer;

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
     * @covers \Librinfo\VarietiesBundle\Form\DataTransformer\SpeciesCodeTransformer::transform
     */
    public function testTransform()
    {
        $test = $this->object->transform('test');
        $this->assertEquals($test, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Form\DataTransformer\SpeciesCodeTransformer::reverseTransform
     */
    public function testReverseTransform()
    {
        $test = strtoupper(trim('TeSt'));
        $reverse = $this->object->reverseTransform('TeSt');
        $this->assertEquals($test, $reverse);
    }
}
