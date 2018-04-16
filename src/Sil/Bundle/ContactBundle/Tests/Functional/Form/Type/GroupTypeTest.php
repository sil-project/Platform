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
use Sil\Bundle\ContactBundle\Entity\Group;
use Sil\Bundle\ContactBundle\Repository\GroupRepository;
use Sil\Bundle\ContactBundle\Form\Type\GroupType;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Bundle\ContactBundle\Form\Type\GroupType
 */
class GroupTypeTest extends BlastTestCase
{
    /**
     * Form factory.
     *
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var Group
     */
    protected $group;

    /**
     * Group repository.
     *
     * @var GroupRepository
     */
    protected $groupRepository;

    public function setUp()
    {
        parent::setup();

        $this->formFactory = $this->container->get('form.factory');

        $this->groupRepository = $this->container->get('sil.repository.contact_group');
        $this->group = new Group('foo');
        $this->groupRepository->add($this->group);
    }

    public function test_submit()
    {
        // Data that will be submitted
        $formData = [
            'name'     => 'bar',
            'parent'   => $this->group->getId(),
        ];

        // Object with wich the object returned by the form will be compared to
        $standard = new Group($formData['name']);
        $standard->setParent($this->group);

        $form = $this->formFactory->create(GroupType::class, []);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($form->getData(), $standard);
    }

    public function tearDown()
    {
        $this->groupRepository->remove($this->group);
    }
}
