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
use Sil\Bundle\ProductBundle\Entity\Product;
use Sil\Bundle\ProductBundle\Form\Product\ProductVariantsType;

class ProductVariantController extends BaseController
{
    public function editVariantsForProductAction(Request $request, string $productId): Response
    {
        $product = $this->findProductOr404($productId);

        $form = $this->formFactory->create(
            ProductVariantsType::class,
            $product
        );

        try {
            $form->handleRequest($request);
        } catch (\Exception $e) {
            $this->addFlash('error', $this->get('translator')->trans('sil.product.flashes.update.' . $e->getMessage()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $variants = $product->getVariants();

            foreach ($variants as $variant) {
                $this->productVariantRepository->update($variant);
            }

            $this->addFlash('success', $this->get('translator')->trans('sil.product.flashes.update.success'));

            if ($request->isXmlHttpRequest()) {
                return $this->render($this->getParameter('blast_ui.template.widget_field_form_group'), [
                    'form' => $form->createView(),
                ]);
            } else {
                return $this->redirect($request->headers->get('referer'));
            }
        }

        return $this->render('@SilProduct/ProductVariant/variants.html.twig', ['form' => $form->createView()]);
    }
}
