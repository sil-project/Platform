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
use Sil\Component\Contact\Model\ContactInterface;
use Sil\Bundle\ContactBundle\Form\DataTransformer\IdToContactTransformer;
use Sil\Bundle\ContactBundle\Tests\Resources\Repository\ContactRepository;
use Sil\Bundle\ContactBundle\Entity\Contact;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Bundle\ContactBundle\Form\DataTransformer\IdToContactTransformer
 */
class IdToContactTransformerTest extends TestCase
{
    public function test_reverse_transform()
    {
        $id = '5405s4df05';

        $contact = new Contact();
        $contact->setId($id);

        $repository = new ContactRepository(ContactInterface::class);

        $repository->add($contact);

        $transformer = new IdToContactTransformer($repository);

        $transformed = $transformer->reverseTransform($id);

        $this->assertInstanceOf(Contact::class, $transformed);
        $this->assertSame($transformed, $contact);
    }
}
