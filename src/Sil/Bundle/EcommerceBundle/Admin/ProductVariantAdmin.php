<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Admin;

use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Resource\Factory\Factory;
use Sil\Bundle\EcommerceBundle\Repository\ChannelRepository;
use Sil\Bundle\EcommerceBundle\Entity\ProductOptionValue;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductVariantAdmin extends SyliusGenericAdmin
{
    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.ecommerce.product_variant';

    protected $baseRouteName = 'admin_ecommerce_product_variant';
    protected $baseRoutePattern = 'ecommerce/product_variant';

    /**
     * @var ProductInterface
     */
    private $product;

    /**
     * @var string
     */
    protected $productAdminCode = 'sil.admin.product';

    /**
     * {@inheritdoc}
     */
    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);

        $list['show_product'] = [
            'template' => 'SilEcommerceBundle:Button:show_product_button.html.twig',
        ];

        return $list;
    }

    public function configureFormFields(FormMapper $mapper)
    {
        $product = $this->getProduct();
        $request = $this->getRequest();

        // Regular edit/create form
        parent::configureFormFields($mapper);

        // Limit the variant option values to the product options
        if ($product) {
            $mapper->add(
                'optionValues',
                'entity',
                [
                    'query_builder' => $this->optionValuesQueryBuilder(),
                    'class'         => ProductOptionValue::class,
                    'multiple'      => true,
                    'required'      => false,
                    'choice_label'  => 'fullName',
                ],
                [
                    'admin_code' => 'sil_ecommerce.admin.product_option_value',
                ]
            );
        }
    }

    /**
     * @return ProductVariantInterface
     */
    public function getNewInstance()
    {
        $object = parent::getNewInstance();
        if ($this->getProduct()) {
            $object->setProduct($this->getProduct());
        }

        $this->buildDefaultPricings($object);

        return $object;
    }

    public function buildDefaultPricings($object)
    {
        /* @var $channelPricingFactory Factory */
        $channelPricingFactory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.channel_pricing');

        /* @var $channelRepository ChannelRepository */
        $channelRepository = $this->getConfigurationPool()->getContainer()->get('sylius.repository.channel');

        foreach ($channelRepository->getAvailableAndActiveChannels() as $channel) {
            $channelPricing = $channelPricingFactory->createNew();
            $channelPricing->setChannelCode($channel->getCode());
            $object->addChannelPricing($channelPricing);
        }

        foreach ($this->getExtensions() as $extension) {
            $extension->alterNewInstance($this, $object);
        }

        return $object;
    }

    /**
     * @return ProductInterface|null
     *
     * @throws \Exception
     */
    public function getProduct()
    {
        if ($this->product) {
            return $this->product;
        }

        if ($this->subject && $product = $this->subject->getProduct()) {
            $this->product = $product;

            return $product;
        }

        $product_id = $this->getRequest()->get('product_id') ?: $this->getRequest()->get('productId');
        if ($product_id) {
            $product = $this->getConfigurationPool()->getContainer()->get('sylius.repository.product')->find($product_id);
            if (!$product) {
                throw new \Exception(sprintf('Unable to find Product with id : %s', $product_id));
            }
            $this->product = $product;

            return $product;
        }

        return null;
    }

    /**
     * @return QueryBuilder
     */
    protected function optionValuesQueryBuilder()
    {
        $repository = $this->getConfigurationPool()->getContainer()->get('sylius.repository.product_option_value');
        $productClass = $this->getConfigurationPool()->getContainer()->getParameter('sil.model.product.class');
        /* todo: check this request */
        $queryBuilder = $repository->createQueryBuilder('o')
                      ->andWhere('o.option IN (SELECT o2 FROM ' . $productClass . ' p LEFT JOIN p.options o2 WHERE p = :product)')
                      ->setParameter('product', $this->product);

        return $queryBuilder;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        if ($object) {
            $id = $object->getId();
            $code = $object->getCode();

            $qb = $this->getModelManager()->createQuery(get_class($object), 'p');

            if ($id !== null) {
                $qb
                    ->where('p.id <> :currentId')
                    ->andWhere('p.code = :currentCode')
                    ->setParameters(
                        [
                            'currentId'   => $id,
                            'currentCode' => $code,
                        ]
                    );
            } else {
                $qb
                    ->where('p.id IS NOT NULL')
                    ->andWhere('p.code = :currentCode')
                    ->setParameter('currentCode', $code);
            }

            if (count($qb->getQuery()->getResult()) != 0) {
                $errorElement
                    ->with('code')
                    ->addViolation('sil.product_variant_code.not_unique')
                    ->end();
            }
        }
    }
}
