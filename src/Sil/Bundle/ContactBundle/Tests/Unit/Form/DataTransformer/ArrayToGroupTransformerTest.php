<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Tests\Unit\Form\DataTransformer;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Sil\Bundle\ContactBundle\Form\DataTransformer\ArrayToGroupTransformer;
use Sil\Bundle\ContactBundle\Entity\Group;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Bundle\ContactBundle\Form\DataTransformer\ArrayToGroupTransformer
 */
class ArrayToGroupTransformerTest extends TestCase
{
    public function test_reverse_transform()
    {
        $group = [
            'name'    => 'foo',
            'parent'  => $this->createMock(Group::class),
        ];

        $transformer = new ArrayToGroupTransformer();

        $transformed = $transformer->reverseTransform($group);

        $this->assertInstanceOf(Group::class, $transformed);
        $this->assertSame($group['name'], $transformed->getName());
        $this->assertSame($group['parent'], $transformed->getParent());
    }

    public function test_reverse_transform_without_name()
    {
        $group = [
            'parent'  => $this->createMock(Group::class),
        ];

        $transformer = new ArrayToGroupTransformer();

        $this->expectException(TransformationFailedException::class);

        $transformer->reverseTransform($group);
    }
}
