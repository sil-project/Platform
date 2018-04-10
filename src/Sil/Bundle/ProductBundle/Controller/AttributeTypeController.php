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
use Sil\Bundle\ProductBundle\Form\AttributeType\CreateType;
use Sil\Bundle\ProductBundle\Form\AttributeType\GeneralType;

class AttributeTypeController extends BaseController
{
    /**
     * Default route.
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->redirectToRoute('sil_product_attribute_type_list');
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
                $this->get('translator')->trans('sil.product.attribute_type.list.page_header.title'),
                'sil_product_attribute_type_list'
            )
        ;

        $pagination = $this->get('sil.repository.product_attribute_type')->createPaginator();
        $pagination->setCurrentPage($request->query->getInt('page', 1));
        $pagination->setMaxPerPage($request->query->getInt('perPage', $this->perPage));

        return $this->render('@SilProduct/AttributeType/list.html.twig', [
            'list' => [
                'headers'     => [
                    [
                        'name'  => 'name',
                        'label' => 'Nom',
                    ], [
                        'name'         => 'type',
                        'label'        => 'Type',
                        'trans_prefix' => 'sil.product.attribute_type.values.',
                    ], [
                        'name'  => 'reusable',
                        'label' => 'Réutilisable',
                    ], [
                        'name'  => 'valuesCount',
                        'label' => 'Nb Attributs',
                    ],
                ],
                'elements'    => $pagination,
                'actions'     => [
                    [
                        'label'       => 'Voir',
                        'icon'        => 'eye',
                        'routeName'   => 'sil_product_attribute_type_show',
                        'routeParams' => [
                            'attributeTypeId' => '%item%.id',
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
    public function showAction(string $attributeTypeId): Response
    {
        $attributeType = $this->findAttributeTypeOr404($attributeTypeId);

        $this->breadcrumbBuilder
            ->addItemWithRoute(
                $this->get('translator')->trans('sil.product.attribute_type.list.page_header.title'),
                'sil_product_attribute_type_list'
            )
            ->addItemWithRoute(
                $attributeType->getName(),
                'sil_product_attribute_type_show',
                ['attributeTypeId' => $attributeTypeId]
            )
        ;

        $attributeTypeGeneralForm = $this->createForm(GeneralType::class, $attributeType, [
            'action' => $this->generateUrl('sil_product_attribute_type_edit', [
                'attributeTypeId' => $attributeType->getId(),
            ]),
        ]);

        return $this->render('@SilProduct/AttributeType/show.html.twig', [
            'attributeType' => $attributeType,
            'form'          => [
                'general' => $attributeTypeGeneralForm->createView(),
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
            $attributeType = $form->getData();

            $this->attributeTypeRepository->add($attributeType);

            $this->addFlash('success', $this->get('translator')->trans('sil.product.flashes.create.success'));

            return $this->redirectToRoute('sil_product_attribute_type_show', [
                'attributeTypeId' => $attributeType->getId(),
            ]);
        }

        return $this->render('@SilProduct/AttributeType/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * edit route.
     *
     * @param Request $request
     * @param string  $attributeTypeId
     *
     * @return Response
     */
    public function editAction(Request $request, string $attributeTypeId): Response
    {
        $attributeType = $this->findAttributeTypeOr404($attributeTypeId);

        $form = $this->createForm(GeneralType::class, $attributeType, [
            'action' => $this->generateUrl('sil_product_attribute_type_edit', [
                'attributeTypeId' => $attributeType->getId(),
            ]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $attributeType = $form->getData();

            $this->attributeTypeRepository->update($attributeType);

            $this->addFlash('success', $this->get('translator')->trans('sil.product.flashes.update.success'));

            if ($request->isXmlHttpRequest()) {
                return $this->render($this->getParameter('blast_ui.template.widget_field_form_group'), [
                    'form' => $form->createView(),
                ]);
            } else {
                return $this->redirect($request->headers->get('referer'));
            }
        }

        return $this->render('@SilProduct/Form/partial.html.twig', ['form' => $form->createView()]);
    }

    /**
     * remove route.
     *
     * @param string $attributeTypeId
     *
     * @return Response
     */
    public function removeAction(string $attributeTypeId): Response
    {
        $attributeType = $this->findAttributeTypeOr404($attributeTypeId);

        if (!$attributeType->canBeDeleted()) {
            $this->addFlash('error', $this->get('translator')->trans('sil.product.flashes.remove_attribute_type.fail'));

            return $this->redirectToRoute('sil_product_attribute_type_show', ['attributeTypeId' => $attributeType->getId()]);
        } else {
            foreach ($attributeType->getDeletableAttributes() as $attr) {
                $this->attributeRepository->remove($attr);
            }
            $this->attributeTypeRepository->remove($attributeType);

            $this->addFlash('success', $this->get('translator')->trans('sil.product.flashes.remove_attribute_type.success'));

            return $this->redirectToRoute('sil_product_attribute_type_list');
        }
    }
}
