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
use Sil\Bundle\AccountBundle\Form\Type\AccountTypeFormType;
use Sil\Component\Account\Repository\AccountTypeRepositoryInterface;

/**
 * AccountType Controller.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class AccountTypeController extends Controller
{
    /**
     * AccountType repository.
     *
     * @var AccountTypeRepositoryInterface
     */
    protected $accountTypeRepository;

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(AccountTypeFormType::class, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accountType = $form->getData();

            $this->accountTypeRepository->add($accountType);

            return $this->redirectToRoute('sil_account_type_show', ['id' => $accountType->getId()]);
        }

        return $this->render('SilAccountBundle:create:account-type.html.twig', [
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
        $accountType = $this->accountTypeRepository->get($id);

        $form = $this->createForm(
            AccountTypeFormType::class,
            $accountType,
            ['action' => $this->generateUrl('sil_account_type_edit', ['id' => $accountType->getId()])]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accountType = $form->getData();

            $this->accountTypeRepository->update($accountType);

            return $this->redirectToRoute('sil_account_type_show', ['id' => $accountType->getId()]);
        }

        if ($request->get('form')) {
            return $this->render('SilAccountBundle:form:account-type-form.html.twig', ['form' => $form->createView()]);
        }

        return $this->render('SilAccountBundle:create:account-type.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function showAction(Request $request)
    {
        $id = $request->get('id');
        $accountType = $this->accountTypeRepository->find($id);

        if (!$accountType) {
            throw new \InvalidArgumentException(sprintf(
                'AccountType with id "%s" does not exist',
                $id
            ));
        }

        return $this->render('SilAccountBundle:show:account-type.html.twig', [
            'accountType' => $accountType,
        ]);
    }

    /**
     * @param AccountTypeRepositoryInterface $repository
     */
    public function setAccountTypeRepository(AccountTypeRepositoryInterface $repository)
    {
        $this->accountTypeRepository = $repository;
    }
}
