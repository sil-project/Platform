<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ProductBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sil\Bundle\ProductBundle\Form\OptionType\CreateType;
use Sil\Bundle\ProductBundle\Form\OptionType\GeneralType;

class OptionTypeController extends BaseController
{
    /**
     * Default route.
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->redirectToRoute('sil_product_option_type_list');
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
        $this->breadcrumbBuilder
            ->addItemWithRoute(
                $this->get('translator')->trans('sil.product.option_type.list.page_header.title'),
                'sil_product_option_type_list'
            )
        ;

        $pagination = $this->get('sil.repository.product_option_type')->createPaginator();
        $pagination->setCurrentPage($request->query->getInt('page', 1));
        $pagination->setMaxPerPage($request->query->getInt('perPage', $this->perPage));

        return $this->render('@SilProduct/OptionType/list.html.twig', [
            'list' => [
                'headers'     => [
                    [
                        'name'  => 'name',
                        'label' => 'Nom',
                    ], [
                        'name'  => 'valuesCount',
                        'label' => 'Nb options',
                    ],
                ],
                'elements'    => $pagination,
                'actions'     => [
                    [
                        'label'       => 'Voir',
                        'icon'        => 'eye',
                        'routeName'   => 'sil_product_option_type_show',
                        'routeParams' => [
                            'optionTypeId' => '%item%.id',
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
     * @param string $attributeTypeId
     *
     * @return Response
     */
    public function showAction(string $optionTypeId): Response
    {
        $optionType = $this->findOptionTypeOr404($optionTypeId);

        $this->breadcrumbBuilder
            ->addItemWithRoute(
                $this->get('translator')->trans('sil.product.option_type.list.page_header.title'),
                'sil_product_option_type_list'
            )
            ->addItemWithRoute(
                $optionType->getName(),
                'sil_product_option_type_show',
                ['optionTypeId' => $optionTypeId]
            )
        ;

        $optionTypeGeneralForm = $this->createForm(GeneralType::class, $optionType, [
            'action' => $this->generateUrl('sil_product_option_type_edit', [
                'optionTypeId' => $optionType->getId(),
            ]),
        ]);

        return $this->render('@SilProduct/OptionType/show.html.twig', [
            'optionType'    => $optionType,
            'form'          => [
                'general' => $optionTypeGeneralForm->createView(),
            ],
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
        $form = $this->createForm(CreateType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $optionType = $form->getData();

            $this->optionTypeRepository->add($optionType);

            $this->addFlash('success', $this->get('translator')->trans('sil.product.flashes.create.success'));

            return $this->redirectToRoute('sil_product_option_type_show', [
                'optionTypeId' => $optionType->getId(),
            ]);
        }

        return $this->render('@SilProduct/OptionType/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * edit route.
     *
     * @param Request $request
     * @param string  $optionTypeId
     *
     * @return Response
     */
    public function editAction(Request $request, string $optionTypeId): Response
    {
        $optionType = $this->findAttributeTypeOr404($optionTypeId);

        $form = $this->createForm(GeneralType::class, $optionType, [
            'action' => $this->generateUrl('sil_product_option_type_edit', [
                'optionTypeId' => $optionType->getId(),
            ]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $optionType = $form->getData();

            $this->optionTypeRepository->update($optionType);

            $this->addFlash('success', $this->get('translator')->trans('sil.product.flashes.update.success'));

            if ($request->isXmlHttpRequest()) {
                return $this->render($this->getParameter('blast_ui.template.widget_field_form_group'), [
                    'form' => $form->createView(),
                ]);
            }

            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('@SilProduct/Form/partial.html.twig', ['form' => $form->createView()]);
    }

    /**
     * remove route.
     *
     * @param string $optionTypeId
     *
     * @return Response
     */
    public function removeAction(string $optionTypeId): Response
    {
        $optionType = $this->findOptionTypeOr404($optionTypeId);

        if (!$optionType->canBeDeleted()) {
            $this->addFlash('error', $this->get('translator')->trans('sil.product.flashes.remove_option_type.fail'));

            return $this->redirectToRoute('sil_product_option_type_show', ['optionTypeId' => $optionType->getId()]);
        } else {
            foreach ($optionType->getDeletableOptions() as $opt) {
                $this->optionRepository->remove($opt);
            }
            $this->optionTypeRepository->remove($optionType);

            $this->addFlash('success', $this->get('translator')->trans('sil.product.flashes.remove_option_type.success'));

            return $this->redirectToRoute('sil_product_option_type_list');
        }
    }
}
