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
use Sil\Bundle\ContactBundle\Form\Type\GroupType;
use Sil\Bundle\ContactBundle\Entity\Group;
use Sil\Bundle\ContactBundle\Entity\Contact;
use Sil\Component\Contact\Repository\ContactRepositoryInterface;
use Sil\Component\Contact\Repository\GroupRepositoryInterface;

/**
 * Group Controller.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class GroupController extends Controller
{
    /**
     * Contact repository.
     *
     * @var ContactRepositoryInterface
     */
    protected $contactRepository;

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
        $form = $this->createForm(GroupType::class, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $group = $this->persistGroup($form->getData());

            if ($request->isXmlHttpRequest()) {
                if ($request->get('list')) {
                    return $this->listAction($request);
                }

                return $this->render('SilContactBundle:block:group-block.html.twig', [
                    'group' => $group,
                ]);
            }

            return $this->redirectToRoute('sil_contact_group_show', ['id' => $group->getId()]);
        }

        return $this->render('SilContactBundle:form:group-form.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');

        if ($id) {
            $group = $this->groupRepository->get($id);

            if (!$group) {
                throw new \InvalidArgumentException(sprintf(
                    'Group with id "%s" does not exist',
                    $id
                ));
            }
        }

        $form = $this->createForm(GroupType::class, $group);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $group = $this->persistGroup($form->getData(), true);

            if ($request->isXmlHttpRequest()) {
                if ($request->get('list')) {
                    return $this->listAction($request);
                }

                return $this->render('SilContactBundle:block:group-block.html.twig', [
                    'group' => $group,
                ]);
            }

            return $this->redirectToRoute('sil_contact_group_show', ['id' => $group->getId()]);
        }

        return $this->render('SilContactBundle:form:group-form.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function showAction(Request $request)
    {
        $id = $request->get('id');

        $group = $this->groupRepository->get($id);

        if (!$group) {
            throw new \InvalidArgumentException(sprintf(
                'Group with id "%s" does not exist',
                $id
            ));
        }

        return $this->render('SilContactBundle:show:group.html.twig', ['group' => $group]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $group = $this->groupRepository->get($request->get('id'));

        if (!$group) {
            throw new \InvalidArgumentException('Trying to remove a non existing group');
        }

        $this->groupRepository->remove($group);

        if ($request->get('list')) {
            return $this->listAction($request);
        }

        return new Response(sprintf(
            'Object %s successfully deleted',
            $group->getId()
        ));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $groupForm = $this->createForm(GroupType::class, []);

        $groups = $this->groupRepository->getRootNodes('name');

        return $this->render('SilContactBundle:list:groups.html.twig', [
            'groups'    => $groups,
            'groupForm' => $groupForm->createView(),
        ]);
    }

    /**
     * @param contact $contact
     *
     * @return Response
     */
    public function getGroupListForContactAction(Contact $contact)
    {
        return $this->render('SilContactBundle:list:contact-groups.html.twig', ['contact' => $contact]);
    }

    /**
     * @param Group $group
     * @param bool  $update
     *
     * @return Group
     */
    private function persistGroup(Group $group, bool $update = false)
    {
        if ($update) {
            $this->groupRepository->update($group);
        } else {
            $this->groupRepository->add($group);
        }

        return $group;
    }

    /**
     * @param ContactRepositoryInterface $repository
     */
    public function setContactRepository(ContactRepositoryInterface $repository)
    {
        $this->contactRepository = $repository;
    }

    /**
     * @param GroupRepositoryInterface $repository
     */
    public function setGroupRepository(GroupRepositoryInterface $repository)
    {
        $this->groupRepository = $repository;
    }
}
