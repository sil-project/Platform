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
use Sil\Bundle\ContactBundle\Form\DataTransformer\ArrayToPhoneTransformer;
use Sil\Bundle\ContactBundle\Repository\ContactRepository;
use Sil\Bundle\ContactBundle\Entity\Phone;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Bundle\ContactBundle\Form\DataTransformer\ArrayToPhoneTransformer
 */
class ArrayToPhoneTransformerTest extends TestCase
{
    public function test_reverse_transform()
    {
        $phone = [
            'number' => '54065465405',
            'type'   => 'foo',
        ];

        $transformer = $this->getTransformer();

        $transformed = $transformer->reverseTransform($phone);

        $this->assertInstanceOf(Phone::class, $transformed);
        $this->assertSame($transformed->getNumber(), $phone['number']);
        $this->assertSame($transformed->getType(), $phone['type']);
    }

    public function test_reverse_transform_without_name()
    {
        $phone = ['type' => 'foo'];

        $transformer = $this->getTransformer();

        $this->expectException(TransformationFailedException::class);

        $transformer->reverseTransform($phone);
    }

    /**
     *  Get ArrayToPhoneTransformer instance.
     *
     * @return ArrayToPhoneTransformer
     */
    private function getTransformer()
    {
        $contactRepository = $this->createMock(ContactRepository::class);

        return new ArrayToPhoneTransformer($contactRepository);
    }
}
