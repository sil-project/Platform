<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sil\Bundle\ContactBundle\Form\Type\ContactType;
use Sil\Bundle\ContactBundle\Form\Type\GroupMemberType;
use Sil\Bundle\ContactBundle\Form\Type\PhoneType;
use Sil\Bundle\ContactBundle\Form\Type\AddressType;
use Sil\Bundle\ContactBundle\Entity\Contact;
use Sil\Bundle\ContactBundle\Entity\Address;
use Sil\Bundle\ContactBundle\Entity\Phone;
use Sil\Component\Contact\Repository\ContactRepositoryInterface;
use Sil\Component\Contact\Repository\PhoneRepositoryInterface;
use Sil\Component\Contact\Repository\AddressRepositoryInterface;
use Sil\Component\Contact\Repository\GroupRepositoryInterface;

/**
 * Contact Controller.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class ContactController extends Controller
{
    /**
     * Contact repository.
     *
     * @var ContactRepositoryInterface
     */
    protected $contactRepository;

    /**
     * Phone repository.
     *
     * @var PhoneRepositoryInterface
     */
    protected $phoneRepository;

    /**
     * Address repository.
     *
     * @var AddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * Group repository.
     *
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(ContactType::class, new Contact());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $this->contactRepository->add($contact);

            return $this->redirectToRoute('sil_contact_show', ['id' => $contact->getId()]);
        }

        return $this->render('SilContactBundle:create:contact.html.twig', [
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');
        $contact = $this->contactRepository->get($id);

        $form = $this->createForm(
            ContactType::class,
            $contact,
            ['action' => $this->generateUrl('sil_contact_edit', ['id' => $contact->getId()])]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $this->contactRepository->update($contact);

            return $this->redirectToRoute('sil_contact_show', ['id' => $contact->getId()]);
        }

        if ($request->get('form')) {
            return $this->render('SilContactBundle:form:contact-form.html.twig', ['form' => $form->createView()]);
        }

        return $this->render('SilContactBundle:create:contact.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function showAction(Request $request)
    {
        $id = $request->get('id');

        $groupForm = $this->createForm(GroupMemberType::class, []);
        $phoneForm = $this->createForm(PhoneType::class, []);
        $addressForm = $this->createForm(AddressType::class, []);

        $contact = $this->contactRepository->find($id);

        if (!$contact) {
            throw new \InvalidArgumentException(sprintf(
                'Contact with id "%s" does not exist',
                $id
            ));
        }

        return $this->render('SilContactBundle:show:contact.html.twig', [
            'contact'     => $contact,
            'groupForm'   => $groupForm->createView(),
            'phoneForm'   => $phoneForm->createView(),
            'addressForm' => $addressForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function setDefaultPhoneAction($phoneId)
    {
        $phone = $this->phoneRepository->get($phoneId);

        if (!$phone) {
            throw new \InvalidArgumentException('Trying to set a non existing phone as default');
        }

        $contact = $this->contactRepository->get($phone->getContact()->getId());

        $contact->setDefaultPhone($phone);

        $this->contactRepository->update($contact);

        return $this->render('SilContactBundle:list:contact-phones.html.twig', ['contact' => $contact]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function setDefaultAddressAction($addressId)
    {
        $address = $this->addressRepository->get($addressId);

        if (!$address) {
            throw new \InvalidArgumentException('Trying to set a non existing address as default');
        }

        $contact = $this->contactRepository->get($address->getContact()->getId());

        $contact->setDefaultAddress($address);

        $this->contactRepository->update($contact);

        return $this->render('SilContactBundle:list:contact-addresses.html.twig', ['contact' => $contact]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function addGroupAction(Request $request)
    {
        $groupMemberValidator = $this->container->get('sil_contact.validator.group_member');
        $form = $this->createForm(GroupMemberType::class, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $group = $data['group'];
            $contactId = $data['member'];

            $contact = $this->contactRepository->get($contactId);

            if (!$groupMemberValidator->isValidMember($group, $contact)) {
                return new JsonResponse(sprintf(
                    'Contact %s is already a member of the hierarchy branch of group %s',
                    $contact->getId(),
                    $group->getId()
                ), 500);
            }

            $group->addMember($contact);
            $contact->addGroup($group);

            $this->groupRepository->update($group);
            $this->contactRepository->update($contact);

            return $this->render('SilContactBundle:list:contact-groups.html.twig', [
                'groups' => $contact->getGroups(),
            ]);
        }

        return $this->render('SilContactBundle:form:contact-group-form.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function removeGroupAction(Request $request)
    {
        $contact = $this->contactRepository->get($request->get('id'));
        $group = $this->groupRepository->get($request->get('groupId'));

        $contact->removeGroup($group);
        $group->removeMember($contact);

        $this->groupRepository->update($group);
        $this->contactRepository->update($contact);

        return $this->render('SilContactBundle:list:contact-groups.html.twig', [
            'groups' => $contact->getGroups(),
        ]);
    }

    /**
     * @param ContactRepositoryInterface $repository
     */
    public function setContactRepository(ContactRepositoryInterface $repository)
    {
        $this->contactRepository = $repository;
    }

    /**
     * @param PhoneRepositoryInterface $repository
     */
    public function setPhoneRepository(PhoneRepositoryInterface $repository)
    {
        $this->phoneRepository = $repository;
    }

    /**
     * @param AddressRepositoryInterface $repository
     */
    public function setAddressRepository(AddressRepositoryInterface $repository)
    {
        $this->addressRepository = $repository;
    }

    /**
     * @param GroupRepositoryInterface $repository
     */
    public function setGroupRepository(GroupRepositoryInterface $repository)
    {
        $this->groupRepository = $repository;
    }
}
