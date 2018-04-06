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
use Sil\Bundle\ProductBundle\Form\Option\GeneralType;
use Sil\Bundle\ProductBundle\Form\Option\CreateType;

class OptionController extends BaseController
{
    /**
     * show route.
     *
     * @param string $optionId
     *
     * @return Response
     */
    public function showAction(string $optionId): Response
    {
        $option = $this->findOptionOr404($optionId);

        $this->breadcrumbBuilder
            ->addItemWithRoute(
                $this->get('translator')->trans('sil.product.option_type.list.page_header.title'),
                'sil_product_option_type_list'
            )
            ->addItemWithRoute(
                $this->get('translator')->trans('sil.product.option.show.page_header.title'),
                'sil_product_option_show',
                ['optionId' => $optionId]
            )
        ;

        $optionGeneralForm = $this->createForm(GeneralType::class, $option, [
            'action' => $this->generateUrl('sil_product_option_edit', [
                'optionId' => $option->getId(),
            ]),
        ]);

        return $this->render('@SilProduct/Option/show.html.twig', [
            'option'    => $option,
            'form'      => [
                'general' => $optionGeneralForm->createView(),
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
        $optionTypeId = $request->get('optionTypeId');
        $optionType = null;

        if ($optionTypeId !== null) {
            $optionType = $this->findOptionTypeOr404($optionTypeId);
        }

        $form = $this->createForm(CreateType::class, [
            'optionType' => $optionType,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $option = $form->getData();

            $this->optionRepository->add($option);

            $this->addFlash('success', $this->get('translator')->trans('sil.product.flashes.create.success'));

            return $this->redirectToRoute('sil_product_option_type_show', [
                'optionTypeId' => $option->getOptionType()->getId(),
            ]);
        }

        return $this->render('@SilProduct/Option/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * edit route.
     *
     * @param Request $request
     * @param string  $optionId
     *
     * @return Response
     */
    public function editAction(Request $request, string $optionId): Response
    {
        $option = $this->findOptionOr404($optionId);

        $form = $this->createform(GeneralType::class, $option, [
            'action' => $this->generateUrl('sil_product_option_edit', [
                'optionId' => $option->getId(),
            ]),
        ]);

        try {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $option = $form->getData();
                $this->optionRepository->update($option);

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
            $this->addFlash('error', $this->get('translator')->trans('sil.product.flashes.update.option.' . $e->getMessage()));
        }

        return $this->render('@SilProduct/Form/partial.html.twig', ['form' => $form->createView()]);
    }
}
