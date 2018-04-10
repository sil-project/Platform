<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\UserBundle\Controller;

use Blast\Bundle\UIBundle\Breadcrumb\BreadcrumbBuilder;
use Blast\Component\Resource\Model\ResourceInterface;
use InvalidArgumentException;
use Sil\Component\User\Repository\UserRepositoryInterface; //Sil\Bundle\UserBundle\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sil\Bundle\UserBundle\Form\Type\CreateUserType;
use Sil\Bundle\UserBundle\Form\Type\EditUserType;

/* Same as Product Bundle, Should be factorized */

class UserController extends Controller
{
    /**
     * @var int
     */
    protected $perPage = 20;

    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var BreadcrumbBuilder
     */
    protected $breadcrumbBuilder;

    /**
     * Default route.
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->redirectToroute('sil_user_list');
    }

    /**
     * list route.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request): Response
    {
        $this->breadcrumbBuilder->addItemWithRoute(
            $this->get('translator')->trans('sil.user.user.list.page_header.title'),
            'sil_user_list'
        );
        $currentPage = $request->query->get('page', 1);
        $perPage = $request->query->get('perPage', $this->perPage);
        $pagination = $this->userRepository->createPaginator();
        $pagination->setCurrentPage($currentPage);
        $pagination->setMaxPerPage($perPage);

        return $this->render('@SilUser/User/list.html.twig', [
            'list' => [
                'headers' => [
                    [
                        'name'  => 'username',
                        /* @todo: trans should be done in the twig template (in UI bundle (or not)) */
                        'label' => $this->get('translator')->trans('sil.user.user.username'),
                    ],
                    [
                        'name'  => 'password',
                        'label' => $this->get('translator')->trans('sil.user.user.password'),
                    ],
                    [
                        'name'  => 'email',
                        'label' => $this->get('translator')->trans('sil.user.user.email'),
                    ],
                    [
                        'name'  => 'enabled',
                        'label' => $this->get('translator')->trans('sil.user.user.enabled'),
                    ],
                ],
                'elements'    => $pagination,
                'actions'     => [
                    [
                        'label'       => 'Voir',
                        'icon'        => 'eye',
                        'routeName'   => 'sil_user_show',
                        'routeParams' => [
                            'userId' => '%item%.id',
                        ],
                    ],
                ],
                'pagination'  => $pagination,
            ],
        ]);
    }

    /**
     * show route.
     *
     * @param string $userId
     *
     * @return Response
     */
    public function showAction(string $userId): Response
    {
        $user = $this->findUserOr404($userId);
        $this->breadcrumbBuilder
            ->addItemWithRoute(
                $this->get('translator')->trans('sil.user.user.list.page_header.title'),
                'sil_user_list'
            )
            ->addItemWithRoute(
                $this->get('translator')->trans('sil.user.user.show.page_header.title'),
                'sil_user_show',
                ['userId' => $userId]
            )
        ;

        return $this->render('@SilUser/User/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * create route.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $form = $this->createForm(CreateUserType::class, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $this->userRepository->add($user);

            $this->addFlash('success', $this->get('translator')->trans('sil.user.user.flashes.create.success'));

            return $this->redirectToRoute('sil_user_show', [
                'userId' => $user->getId(),
            ]);
        }

        return $this->render('@SilUser/User/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function editAction(Request $request, string $userId): Response
    {
        $user = $this->findUserOr404($userId);

        $form = $this->createForm(EditUserType::class, $user, [
            'action' => $this->generateUrl('sil_user_edit', [
                'userId' => $user->getId(),
            ]),
        ]);

        try {
            $form->handleRequest($request);
        } catch (\Exception $e) {
            $this->addFlash('error', $this->get('translator')->trans('sil.user.user.flashes.update.error'));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $this->userRepository->update($user);

            $this->addFlash('success', $this->get('translator')->trans('sil.user.user.flashes.update.success'));

            if ($request->isXmlHttpRequest()) {
                return $this->render($this->getParameter('blast_ui.template.widget_field_form_group'), [
                    'form' => $form->createView(),
                ]);
            } else {
                return $this->redirect($request->headers->get('referer'));
            }
        }

        return $this->render('@SilUser/User/edit.html.twig', ['user' => $user, 'form' => $form->createView()]);
    }

    /* @todo: move this or not in a BaseController */
    protected function findUserOr404(string $userId): ResourceInterface
    {
        try {
            return $this->userRepository->get($userId);
        } catch (InvalidArgumentException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function setUserRepository(UserRepositoryInterface $userRepository): void
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param BreadcrumbBuilder $breadcrumbBuilder
     */
    public function setBreadcrumbBuilder(BreadcrumbBuilder $breadcrumbBuilder): void
    {
        $this->breadcrumbBuilder = $breadcrumbBuilder;
    }

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory): void
    {
        $this->formFactory = $formFactory;
    }
}
