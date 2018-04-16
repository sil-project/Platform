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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sil\Bundle\ContactBundle\Entity\Address;
use Sil\Bundle\ContactBundle\Entity\Contact;
use Sil\Bundle\ContactBundle\Form\Type\AddressType;
use Sil\Component\Contact\Repository\ContactRepositoryInterface;
use Sil\Component\Contact\Repository\AddressRepositoryInterface;

/**
 * Address Controller.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class AddressController extends Controller
{
    /**
     * Contact repository.
     *
     * @var ContactRepositoryInterface
     */
    protected $contactRepository;

    /**
     * Address repository.
     *
     * @var AddressRepositoryInterface
     */
    protected $addressRepository;

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $template = $request->isXmlHttpRequest() ?
            'SilContactBundle:form:address-form.html.twig' :
            'SilContactBundle:create:address.html.twig'
        ;
        $form = $this->createForm(AddressType::class, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = $this->persistAddress($form->getData());

            if ($request->isXmlHttpRequest()) {
                if ($request->get('list')) {
                    return $this->getAddressListForContactAction($address->getContact());
                }

                return $this->render('SilContactBundle:block:address-block.html.twig', [
                    'address'          => $address,
                    'isDefaultAddress' => false,
                ]);
            }

            return $this->redirectToRoute('sil_contact_address_show', ['id' => $address->getId()]);
        }

        return $this->render($template, ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        $template = $request->isXmlHttpRequest() ?
            'SilContactBundle:form:address-form.html.twig' :
            'SilContactBundle:create:address.html.twig'
        ;

        if ($id) {
            $address = $this->addressRepository->get($id);

            if (!$address) {
                throw new \InvalidArgumentException(sprintf(
                    'Address with id "%s" does not exist',
                    $id
                ));
            }
        }

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = $this->persistAddress($form->getData(), true);

            if ($request->isXmlHttpRequest()) {
                if ($request->get('list')) {
                    return $this->getAddressListForContactAction($address->getContact());
                }

                return $this->render('SilContactBundle:block:address-block.html.twig', [
                    'address'          => $address,
                    'isDefaultAddress' => false,
                ]);
            }

            return $this->redirectToRoute('sil_contact_address_show', ['id' => $address->getId()]);
        }

        return $this->render($template, ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function showAction(Request $request)
    {
        $id = $request->get('id');

        $address = $this->addressRepository->find($id);

        if (!$address) {
            throw new \InvalidArgumentException(sprintf(
                'Address with id "%s" does not exist',
                $id
            ));
        }

        return $this->render('SilContactBundle:show:address.html.twig', ['address' => $address]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $address = $this->addressRepository->get($request->get('id'));

        if (!$address) {
            throw new \InvalidArgumentException('Trying to remove a non existing address');
        }

        if ($address->getContact()) {
            $contact = $this->contactRepository->get($address->getContact()->getId());

            if ($contact) {
                $contact->removeAddress($address);
                $this->contactRepository->update($contact);
            }
        } else {
            $this->addressRepository->remove($address);
        }

        if ($request->get('list')) {
            return $this->getAddressListForContactAction($address->getContact());
        }

        return new Response(sprintf(
            'Object %s successfully deleted',
            $address->getId()
        ));
    }

    /**
     * @param Contact $contact
     *
     * @return Response
     */
    public function getAddressListForContactAction(Contact $contact)
    {
        return $this->render('SilContactBundle:list:contact-addresses.html.twig', ['contact' => $contact]);
    }

    /**
     * @param Address $address
     * @param bool    $update
     *
     * @return Address
     */
    private function persistAddress(Address $address, bool $update = false)
    {
        if ($update) {
            $this->addressRepository->update($address);
        } else {
            $this->addressRepository->add($address);
        }

        if ($address->getContact()) {
            $this->contactRepository->update($address->getContact());
        }

        return $address;
    }

    /**
     * @param ContactRepositoryInterface $repository
     */
    public function setContactRepository(ContactRepositoryInterface $repository)
    {
        $this->contactRepository = $repository;
    }

    /**
     * @param AddressRepositoryInterface $repository
     */
    public function setAddressRepository(AddressRepositoryInterface $repository)
    {
        $this->addressRepository = $repository;
    }
}
