<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Tests\Functional\Form\Type;

use Symfony\Component\Form\FormFactory;
use Blast\Bundle\TestsBundle\Functional\BlastTestCase;
use Sil\Bundle\ContactBundle\Entity\Contact;
use Sil\Bundle\ContactBundle\Form\Type\ContactType;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Bundle\ContactBundle\Form\Type\ContactType
 */
class ContactTypeTest extends BlastTestCase
{
    /**
     * Form factory.
     *
     * @var FormFactory
     */
    protected $formFactory;

    public function setUp()
    {
        parent::setup();

        $this->formFactory = $this->container->get('form.factory');
    }

    public function test_submit()
    {
        // Data that will be submitted
        $formData = [
            'firstName'   => 'foo',
            'lastName'    => 'baz',
            'title'       => Contact::TITLE_MR,
            'email'       => 'foo.baz@bar.buz',
            'position'    => 'developper',
        ];

        // Object with wich the object returned by the form will be compared to
        $standard = new Contact();

        $standard->setFirstName($formData['firstName']);
        $standard->setLastName($formData['lastName']);
        $standard->setTitle($formData['title']);
        $standard->setEmail($formData['email']);
        $standard->setPosition($formData['position']);

        $form = $this->formFactory->create(ContactType::class, new Contact());

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($form->getData(), $standard);
    }
}
