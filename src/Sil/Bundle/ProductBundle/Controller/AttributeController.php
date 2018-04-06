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
use Sil\Bundle\ProductBundle\Form\Attribute\GeneralType;
use Sil\Bundle\ProductBundle\Form\Attribute\CreateReusableType;

class AttributeController extends BaseController
{
    /**
     * show route.
     *
     * @param string $attributeId
     *
     * @return Response
     */
    public function showAction(string $attributeId): Response
    {
        $attribute = $this->findAttributeOr404($attributeId);

        $this->breadcrumbBuilder
            ->addItemWithRoute(
                $this->get('translator')->trans('sil.product.attribute.list.page_header.title'),
                'sil_product_attribute_type_list'
            )
            ->addItemWithRoute(
                $this->get('translator')->trans('sil.product.attribute.show.page_header.title'),
                'sil_product_attribute_show',
                ['attributeId' => $attributeId]
            )
        ;

        $attributeGeneralForm = $this->createForm(GeneralType::class, $attribute, [
            'action' => $this->generateUrl('sil_product_attribute_edit', [
                'attributeId' => $attribute->getId(),
            ]),
        ]);

        return $this->render('@SilProduct/Attribute/show.html.twig', [
            'attribute' => $attribute,
            'form'      => [
                'general' => $attributeGeneralForm->createView(),
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
        $attributeTypeId = $request->get('attributeTypeId');
        $attributeType = null;

        if ($attributeTypeId !== null) {
            $attributeType = $this->findAttributeTypeOr404($attributeTypeId);
        }

        $form = $this->createForm(CreateReusableType::class, [
            'attributeType' => $attributeType,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $attribute = $form->getData();

            $this->attributeRepository->add($attribute);

            $this->addFlash('success', $this->get('translator')->trans('sil.product.flashes.create.success'));

            return $this->redirectToRoute('sil_product_attribute_type_show', [
                'attributeTypeId' => $attribute->getAttributeType()->getId(),
            ]);
        }

        return $this->render('@SilProduct/Attribute/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * edit route.
     *
     * @param Request $request
     * @param string  $attributeId
     *
     * @return Response
     */
    public function editAction(Request $request, string $attributeId): Response
    {
        $attribute = $this->findAttributeOr404($attributeId);

        $form = $this->createform(GeneralType::class, $attribute, [
            'action' => $this->generateUrl('sil_product_attribute_edit', [
                'attributeId' => $attribute->getId(),
            ]),
        ]);

        try {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $attribute = $form->getData();
                $this->attributeRepository->update($attribute);

                $this->addFlash('success', $this->get('translator')->trans('sil.product.flashes.update.success'));

                if ($request->isXmlHttpRequest()) {
                    return $this->render($this->getParameter('blast_ui.template.widget_field_form_group'), [
                        'form' => $form->createView(),
                    ]);
                } else {
                    return $this->redirect($request->headers->get('referer'));
                }
            }
        } catch (\InvalidArgumentException $e) {
            $this->addFlash('error', $this->get('translator')->trans('sil.product.flashes.update.attribute.' . $e->getMessage()));
        }

        return $this->render('@SilProduct/Form/partial.html.twig', ['form' => $form->createView()]);
    }
}
