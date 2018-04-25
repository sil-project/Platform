<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\AccountBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sil\Bundle\AccountBundle\Form\Type\AccountFormType;
use Sil\Component\Account\Repository\AccountRepositoryInterface;
use Sil\Bundle\ContactBundle\Form\Type\ContactType;
use Sil\Bundle\ContactBundle\Entity\Contact;
use Sil\Component\Contact\Repository\ContactRepositoryInterface;

/**
 * Account Controller.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class AccountController extends Controller
{
    /**
     * Account repository.
     *
     * @var AccountRepositoryInterface
     */
    protected $accountRepository;

    /**
     * Contact repository.
     *
     * @var ContactRepositoryInterface
     */
    protected $contactRepository;

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(AccountFormType::class, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $account = $form->getData();

            $this->accountRepository->add($account);

            return $this->redirectToRoute('sil_account_show', ['id' => $account->getId()]);
        }

        return $this->render('SilAccountBundle:create:account.html.twig', [
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
        $account = $this->accountRepository->get($id);

        $form = $this->createForm(
            AccountFormType::class,
            $account,
            ['action' => $this->generateUrl('sil_account_edit', ['id' => $account->getId()])]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $account = $form->getData();

            $this->accountRepository->update($account);

            return $this->redirectToRoute('sil_account_show', ['id' => $account->getId()]);
        }

        if ($request->get('form')) {
            return $this->render('SilAccountBundle:form:account-form.html.twig', ['form' => $form->createView()]);
        }

        return $this->render('SilAccountBundle:create:account.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function showAction(Request $request)
    {
        $id = $request->get('id');
        $contactForm = $this->createForm(ContactType::class, new Contact());
        $account = $this->accountRepository->find($id);

        if (!$account) {
            throw new \InvalidArgumentException(sprintf(
                'Account with id "%s" does not exist',
                $id
            ));
        }

        return $this->render('SilAccountBundle:show:account.html.twig', [
            'account'       => $account,
            'contactForm'   => $contactForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function setDefaultContactAction($accountId)
    {
        $contact = $this->contactRepository->get($contactId);

        if (!$contact) {
            throw new \InvalidArgumentException('Trying to set a non existing contact as default');
        }

        $account = $this->accountRepository->get($contact->getContact()->getId());

        $account->setDefaultPhone($contact);

        $this->accountRepository->update($account);

        return $this->render('SilContactBundle:list:account-contacts.html.twig', ['account' => $account]);
    }

    /**
     * @param AccountRepositoryInterface $repository
     */
    public function setAccountRepository(AccountRepositoryInterface $repository)
    {
        $this->accountRepository = $repository;
    }

    /**
     * @param ContactRepositoryInterface $repository
     */
    public function setContactRepository(ContactRepositoryInterface $repository)
    {
        $this->contactRepository = $repository;
    }
}
