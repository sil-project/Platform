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
use Sil\Bundle\ProductBundle\Form\Product\CreateType;
use Sil\Bundle\ProductBundle\Form\Product\GeneralType;
use Sil\Bundle\ProductBundle\Form\Attribute\GeneralType as AttributeGeneralType;
use Sil\Bundle\ProductBundle\Form\Attribute\ChooseType;
use Sil\Bundle\ProductBundle\Form\Attribute\AttributeSelectorType;
use Sil\Bundle\ProductBundle\Form\OptionType\OptionTypeSelectorType;
use Sil\Bundle\ProductBundle\Entity\Product;
use Sil\Component\Product\Model\ProductInterface;
use Sil\Component\Product\Service\ProductService;

class ProductController extends BaseController
{
    /**
     * @var ProductService
     */
    protected $productVariantGenerator;

    /**
     * Default route.
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->redirectToroute('sil_product_list');
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
                $this->get('translator')->trans('sil.product.product.list.page_header.title'),
                'sil_product_list'
            )
        ;

        $currentPage = $request->get('page', 1);
        $perPage = $request->get('perPage', $this->perPage);

        $pagination = $this->productRepository->createPaginator();
        $pagination->setCurrentPage($currentPage);
        $pagination->setMaxPerPage($perPage);

        return $this->render('@SilProduct/Product/list.html.twig', [
            'list' => [
                'headers'     => [
                    [
                        'name'  => 'name',
                        'label' => 'Nom',
                    ], [
                        'name'  => 'code',
                        'label' => 'Référence',
                    ], [
                        'name'  => 'enabled',
                        'label' => 'Actif',
                    ],
                ],
                'elements'    => $pagination,
                'actions'     => [
                    [
                        'label'       => 'Voir',
                        'icon'        => 'eye',
                        'routeName'   => 'sil_product_show',
                        'routeParams' => [
                            'productId' => '%item%.id',
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
     * @param string $productId
     *
     * @return Response
     */
    public function showAction(string $productId): Response
    {
        $product = $this->findProductOr404($productId);

        $this->breadcrumbBuilder
            ->addItemWithRoute(
                $this->get('translator')->trans('sil.product.product.list.page_header.title'),
                'sil_product_list'
            )
            ->addItemWithRoute(
                $this->get('translator')->trans('sil.product.product.show.page_header.title', ['%ref%' => $product->getCode()]),
                'sil_product_show',
                ['productId' => $productId]
            )
        ;

        return $this->render('@SilProduct/Product/show.html.twig', [
            'product' => $product,
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
        $form = $this->createForm(CreateType::class, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $this->productVariantGenerator->generateVariantsForProduct($product);

            $this->productRepository->add($product);

            $this->addFlash('success', $this->get('translator')->trans('sil.product.flashes.create.success'));

            return $this->redirectToRoute('sil_product_show', [
                'productId' => $product->getId(),
            ]);
        }

        return $this->render('@SilProduct/Product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * edit route.
     *
     * @param Request $request
     * @param string  $productId
     *
     * @return Response
     */
    public function editAction(Request $request, string $productId): Response
    {
        $product = $this->findProductOr404($productId);

        $form = $this->createForm(GeneralType::class, $product, [
            'action' => $this->generateUrl('sil_product_edit', [
                'productId' => $product->getId(),
            ]),
        ]);

        try {
            $form->handleRequest($request);
        } catch (\Exception $e) {
            $this->addFlash('error', $this->get('translator')->trans('sil.product.flashes.update.' . $e->getMessage()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $this->productRepository->update($product);

            $this->addFlash('success', $this->get('translator')->trans('sil.product.flashes.update.success'));

            if ($request->isXmlHttpRequest()) {
                return $this->render($this->getParameter('blast_ui.template.widget_field_form_group'), [
                    'form' => $form->createView(),
                ]);
            } else {
                return $this->redirect($request->headers->get('referer'));
            }
        }

        return $this->render('@SilProduct/Product/edit.html.twig', ['product' => $product, 'form' => $form->createView()]);
    }

    /**
     * update route.
     *
     * @param Request $request
     * @param string  $productId
     *
     * @return Response
     */
    public function updateAttributesAction(Request $request, string $productId): Response
    {
        $product = $this->findProductOr404($productId);

        if ($request->getMethod() === Request::METHOD_POST) {
            // This method is a manual form handling because of difficulties to do it with a regular Symfony form
            $this->handleUpdateAttributesRawForm($product, $request->request);

            return $this->redirectToRoute('sil_product_show', ['productId' => $product->getId()]);
        } else {
            return $this->render('@SilProduct/Product/update_attributes.html.twig', ['product' => $product]);
        }
    }

    /**
     * create route.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addAttributeAction(Request $request, string $productId): Response
    {
        $product = $this->findProductOr404($productId);
        $attributeType = null;

        $attributeTypeId = $request->get('product_attribute_choose', ['attributeType' => $request->get('attributeType')])['attributeType'];

        if ($attributeTypeId !== null) {
            $attributeType = $this->findAttributeTypeOr404($attributeTypeId);
        }

        $form = $this->createform(
            ChooseType::class,
            ['attributeType' => $attributeType],
            [
                'action'        => $this->generateUrl('sil_product_add_attribute', [
                    'productId' => $product->getId(),
                ]),
                'attributeType' => $attributeType,
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $attributeData = $form->getData();

            if ($attributeData['attributeType']->isReusable()) {
                $attributeData['value'] = $request->request->get('product_attribute_choose', ['value' => null])['value'];
                $attribute = $this->findAttributeOr404($attributeData['value']);
            } else {
                $attribute = new $this->attributeClass($attributeType, $attributeData['value']);
                $this->attributeRepository->add($attribute);
            }

            try {
                $product->addAttribute($attribute);
                $this->productRepository->update($product);

                $this->addFlash('success', $this->get('translator')->trans('sil.product.flashes.add_attribute.success'));
            } catch (\InvalidArgumentException $e) {
                $this->addFlash('error', $this->get('translator')->trans('sil.product.flashes.add_attribute.fail'));
            }

            return $this->forward('sil_product.controller.product:showAction', [
                'productId' => $productId,
            ]);
        }

        return $this->render('@SilProduct/Attribute/Form/choose.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * remove attribute route.
     *
     * @param Request $request
     * @param string  $productId
     * @param string  $attributeId
     *
     * @return [type]
     */
    public function removeAttributeAction(Request $request, string $productId, string $attributeId)
    {
        $product = $this->findProductOr404($productId);
        $attribute = $this->findAttributeOr404($attributeId);

        $product->removeAttribute($attribute);
        $this->productRepository->update($product);

        if (!$attribute->getAttributeType()->isReusable()) {
            $this->attributeRepository->remove($attribute);
        }

        return $this->redirectToRoute('sil_product_show', ['productId' => $product->getId()]);
    }

    /**
     * display attribute selector form.
     *
     * @param Request $request
     * @param string  $attributeId
     * @param string  $productId
     * @param int     $formIndex
     *
     * @return Response
     */
    public function selectAttributeForProductAction(Request $request, string $attributeId, string $productId, ?int $formIndex = 0): Response
    {
        $attribute = $this->findAttributeOr404($attributeId);
        $product = $this->findProductOr404($productId);

        $form = $this->formFactory->createNamed(
            $formIndex,
            AttributeSelectorType::class,
            $attribute
        );

        // Form submit is handled in self::updateAttributesAction

        return $this->render('@SilProduct/Product/attribute_selector.html.twig', ['form' => $form->createView()]);
    }

    /**
     * display attribute simple form.
     *
     * @param Request $request
     * @param string  $attributeId
     * @param string  $productId
     * @param int     $formIndex
     *
     * @return Response
     */
    public function updateAttributeForProductAction(Request $request, string $attributeId, string $productId, ?int $formIndex = 0): Response
    {
        $attribute = $this->findAttributeOr404($attributeId);
        $product = $this->findProductOr404($productId);

        $form = $this->formFactory->createNamed(
            $formIndex,
            AttributeGeneralType::class,
            $attribute
        );

        // Form submit is handled in self::updateAttributesAction

        return $this->render('@SilProduct/Attribute/Form/edit.html.twig', ['form' => $form->createView()]);
    }

    public function updateOptionsAction(Request $request, string $productId): Response
    {
        $product = $this->findProductOr404($productId);

        $form = $this->formFactory->create(
            OptionTypeSelectorType::class,
            $product
        );

        try {
            $form->handleRequest($request);
        } catch (\Exception $e) {
            $this->addFlash('error', $this->get('translator')->trans('sil.product.flashes.update.' . $e->getMessage()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $this->productRepository->update($product);

            $this->addFlash('success', $this->get('translator')->trans('sil.product.flashes.update.success'));

            if ($request->isXmlHttpRequest()) {
                return $this->render($this->getParameter('blast_ui.template.widget_field_form_group'), [
                    'form' => $form->createView(),
                ]);
            } else {
                return $this->redirect($request->headers->get('referer'));
            }
        }

        return $this->render('@SilProduct/Product/select_options.html.twig', ['product' => $product, 'form' => $form->createView()]);
    }

    /**
     * handle product's attributes update form.
     *
     * @param ProductInterface $product
     * @param Traversable      $requestData
     */
    private function handleUpdateAttributesRawForm(ProductInterface $product, \Traversable $requestData): void
    {
        // Temporary clean all attributes from product (without flush changes !)

        foreach ($product->getAttributes() as $attr) {
            $product->removeAttribute($attr);
        }

        foreach ($requestData as $formIndex => $formData) {
            if (!isset($formData['specificName'])) {         // Choosing an existing reusable attribute
                $attributeType = $this->findAttributeTypeOr404($formData['attributeType']);
                $attribute = $this->findAttributeOr404($formData['value']);

                if ($attribute->getAttributeType() === $attributeType) {
                    $product->addAttribute($attribute);
                }
            } elseif (isset($formData['specificName'])) {    // Updating an existing basic attribute
                $attributeType = $this->findAttributeTypeOr404($formData['attributeType']);
                $attribute = $this->findAttributeOr404($formData['id']);

                if ($formData['specificName'] === '') {
                    $formData['specificName'] = null;
                }

                $attribute->setSpecificName($formData['specificName']);
                $attribute->setValue($formData['value']);

                if ($attribute->getAttributeType() === $attributeType) {
                    $product->addAttribute($attribute);
                }
            } else {
                throw new \InvalidArgumentException(sprintf('the form named « %s » is not managed by this controler action', $formName));
            }
        }

        // Update all product attributes

        foreach ($product->getAttributes() as $attr) {
            $this->attributeRepository->update($attr);
        }

        $this->productRepository->update($product);
    }

    /**
     * @param ProductService $productVariantGenerator
     */
    public function setProductVariantGenerator(ProductService $productVariantGenerator): void
    {
        $this->productVariantGenerator = $productVariantGenerator;
    }
}
