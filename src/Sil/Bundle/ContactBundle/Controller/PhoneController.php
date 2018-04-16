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
use Sil\Bundle\ContactBundle\Form\Type\PhoneType;
use Sil\Bundle\ContactBundle\Entity\Phone;
use Sil\Bundle\ContactBundle\Entity\Contact;
use Sil\Component\Contact\Repository\ContactRepositoryInterface;
use Sil\Component\Contact\Repository\PhoneRepositoryInterface;

/**
 * Phone Controller.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class PhoneController extends Controller
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
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $template = $request->isXmlHttpRequest() ?
            'SilContactBundle:form:phone-form.html.twig' :
            'SilContactBundle:create:phone.html.twig'
        ;

        $form = $this->createForm(PhoneType::class, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $phone = $this->persistPhone($form->getData());

            if ($request->isXmlHttpRequest()) {
                if ($request->get('list')) {
                    return $this->getPhoneListForContactAction($phone->getContact());
                }

                return $this->render('SilContactBundle:block:phone-block.html.twig', [
                    'phone'          => $phone,
                    'isDefaultPhone' => false,
                ]);
            }

            return $this->redirectToRoute('sil_contact_phone_show', ['id' => $phone->getId()]);
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
            'SilContactBundle:form:phone-form.html.twig' :
            'SilContactBundle:create:phone.html.twig'
        ;

        if ($id) {
            $phone = $this->phoneRepository->get($id);

            if (!$phone) {
                throw new \InvalidArgumentException(sprintf(
                    'Phone with id "%s" does not exist',
                    $id
                ));
            }
        }

        $form = $this->createForm(PhoneType::class, $phone);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $phone = $this->persistPhone($form->getData(), true);

            if ($request->isXmlHttpRequest()) {
                if ($request->get('list')) {
                    return $this->getPhoneListForContactAction($phone->getContact());
                }

                return $this->render('SilContactBundle:block:phone-block.html.twig', [
                    'phone'          => $phone,
                    'isDefaultPhone' => false,
                ]);
            }

            return $this->redirectToRoute('sil_contact_phone_show', ['id' => $phone->getId()]);
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

        $phone = $this->phoneRepository->get($id);

        if (!$phone) {
            throw new \InvalidArgumentException(sprintf(
                'Phone with id "%s" does not exist',
                $id
            ));
        }

        return $this->render('SilContactBundle:show:phone.html.twig', ['phone' => $phone]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $phone = $this->phoneRepository->get($request->get('id'));

        if (!$phone) {
            throw new \InvalidArgumentException('Trying to remove a non existing phone');
        }

        if ($phone->getContact()) {
            $contact = $this->contactRepository->get($phone->getContact()->getId());

            if ($contact) {
                $contact->removePhone($phone);
                $this->contactRepository->update($contact);
            }
        } else {
            $this->phoneRepository->remove($phone);
        }

        if ($request->get('list')) {
            return $this->getPhoneListForContactAction($phone->getContact());
        }

        return new Response(sprintf(
            'Object %s successfully deleted',
            $phone->getId()
        ));
    }

    /**
     * @param Contact $contact
     *
     * @return Response
     */
    public function getPhoneListForContactAction(Contact $contact)
    {
        return $this->render('SilContactBundle:list:contact-phones.html.twig', ['contact' => $contact]);
    }

    /**
     * @param Phone $phone
     * @param bool  $update
     *
     * @return Phone
     */
    private function persistPhone(Phone $phone, bool $update = false)
    {
        if ($update) {
            $this->phoneRepository->update($phone);
        } else {
            $this->phoneRepository->add($phone);
        }

        if ($phone->getContact()) {
            $this->contactRepository->update($phone->getContact());
        }

        return $phone;
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
}
