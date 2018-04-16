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
use Sil\Bundle\ContactBundle\Entity\Contact;
use Sil\Bundle\ContactBundle\Repository\ContactRepository;
use Sil\Bundle\ContactBundle\Repository\GroupRepository;
use Sil\Bundle\ContactBundle\Form\Type\GroupMemberType;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Bundle\ContactBundle\Form\Type\GroupMemberType
 */
class GroupMemberTypeTest extends BlastTestCase
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
     * @var Contact
     */
    protected $member;

    /**
     * Contact repository.
     *
     * @var ContactRepository
     */
    protected $contactRepository;

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

        $this->contactRepository = $this->container->get('sil.repository.contact');
        $this->member = new Contact();
        $this->contactRepository->add($this->member);

        $this->groupRepository = $this->container->get('sil.repository.contact_group');
        $this->group = new Group('foo');
        $this->groupRepository->add($this->group);
    }

    public function test_submit()
    {
        // Data that will be submitted
        $formData = [
            'group'  => $this->group->getId(),
            'member' => $this->member->getId(),
        ];

        $form = $this->formFactory->create(GroupMemberType::class, []);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $data = $form->getData();

        $this->assertSame($data['group']->getId(), $formData['group']);
        $this->assertSame($data['member'], $formData['member']);
    }

    public function tearDown()
    {
        $this->contactRepository->remove($this->member);
        $this->groupRepository->remove($this->group);
    }
}
