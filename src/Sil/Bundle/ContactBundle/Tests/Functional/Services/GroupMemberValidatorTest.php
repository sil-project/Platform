<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Tests\Functional\Services;

use Blast\Bundle\TestsBundle\Functional\BlastTestCase;
use Sil\Bundle\ContactBundle\Entity\Contact;
use Sil\Bundle\ContactBundle\Entity\Group;
use Sil\Bundle\ContactBundle\Repository\GroupRepository;
use Sil\Bundle\ContactBundle\Services\GroupMemberValidator;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Bundle\ContactBundle\Services\GroupMemberValidator
 */
class GroupMemberValidatorTest extends BlastTestCase
{
    /**
     * Group repository.
     *
     * @var GroupRepository
     */
    protected $groupRepository;

    /**
     * Contact repository.
     *
     * @var ContactRepository
     */
    protected $contactRepository;

    /**
     * Group member validator.
     *
     * @var GroupMemberValidator
     */
    protected $validator;

    public function setUp()
    {
        parent::setup();

        $this->groupRepository = $this->container->get('sil.repository.contact_group');
        $this->contactRepository = $this->container->get('sil.repository.contact');
        $this->validator = $this->container->get('sil_contact.validator.group_member');

        $this->customerGroup = new Group('Customer');
        $this->supplierGroup = new Group('Supplier');
        $this->childCustomerGroup = new Group('Webshop customer');

        $this->groupRepository->add($this->customerGroup);
        $this->groupRepository->add($this->supplierGroup);

        $this->childCustomerGroup->setParent($this->customerGroup);

        $this->groupRepository->add($this->childCustomerGroup);
    }

    public function test_is_valid_member()
    {
        $contact = new Contact();
        $this->contactRepository->add($contact);

        // Customer group is a root node
        $this->customerGroup = $this->groupRepository->findOneBy(['name' => 'Customer']);
        // Webshop customer group is a child of customer group
        $this->childCustomerGroup = $this->groupRepository->findOneBy(['name' => 'Webshop customer']);
        // Supplier group is another root node
        $this->supplierGroup = $this->groupRepository->findOneBy(['name' => 'Supplier']);

        // contact can be a member of customer group
        $this->assertTrue($this->validator->isValidMember($this->customerGroup, $contact));

        $contact->addGroup($this->customerGroup);
        $this->contactRepository->update($contact);

        // contact can be a member of supplier group while being a member of customer group as well
        $this->assertTrue($this->validator->isValidMember($this->supplierGroup, $contact));
        // as contact is already a member of customer group it cannot be a member of one of its children or parents
        $this->assertFalse($this->validator->isValidMember($this->childCustomerGroup, $contact));
    }

    public function tearDown()
    {
        $this->groupRepository->remove($this->childCustomerGroup);
        $this->groupRepository->remove($this->customerGroup);
        $this->groupRepository->remove($this->supplierGroup);
    }
}
