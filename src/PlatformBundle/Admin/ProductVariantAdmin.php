<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PlatformBundle\Admin;

use Blast\Bundle\CoreBundle\Admin\Traits\HandlesRelationsAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Blast\Bundle\ResourceBundle\Sonata\Admin\ResourceAdmin;
use Sil\Component\Stock\Query\StockItemQueriesInterface;
use Sil\Component\Stock\Repository\LocationRepositoryInterface;
use Sil\Component\Stock\Repository\StockUnitRepositoryInterface;
use Sil\Component\Stock\Model\StockItemInterface;
use Sil\Component\Stock\Model\Location;
use Sil\Component\Stock\Model\LocationType;
use Sil\Bundle\EcommerceBundle\Entity\ProductOptionValue;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductVariantAdmin extends ResourceAdmin
{
    use HandlesRelationsAdmin {
        configureFormFields as configFormHandlesRelations;
        configureShowFields as configShowHandlesRelations;
    }

    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.product_variant';

    protected $baseRouteName = 'admin_ecommerce_product_variant';
    protected $baseRoutePattern = 'product_variant';

    /**
     * @var ProductInterface
     */
    private $product;

    /**
     * @var StockItemQueriesInterface
     */
    protected $stockItemQueries;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    public function getNewInstance()
    {
        $object = parent::getNewInstance();
        if (method_exists(get_class($object), 'setCurrentLocale')) {
            $defaultLocale = $this->getConfigurationPool()->getContainer()->get('sylius.locale_provider')->getDefaultLocaleCode();
            $object->setCurrentLocale($defaultLocale);
            $object->setFallbackLocale($defaultLocale);
        }

        foreach ($this->getExtensions() as $extension) {
            $extension->alterNewInstance($this, $object);
        }

        if ($this->getProduct()) {
            $object->setProduct($this->getProduct());
        }

        $this->buildDefaultPricings($object);

        return $object;
    }

    public function getFactoryName()
    {
        throw new \Exception(sprintf(
            '%s have to be impelmented in %s',
            __FUNCTION__ /*__METHOD__*/,
            get_class($this)  /* __CLASS__ */
        ));

        return null;
    }

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

    public function configureShowFields(ShowMapper $mapper)
    {
        parent::configureShowFields($mapper);

        $productVariant = $this->getSubject();

        if ($productVariant && !$productVariant->isTracked()) {
            $this->removeShowTab('sil.stock.stock_item.show.tab.stocks', $mapper);
        }
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

        $this->removeTab('default', $mapper);
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
            $channelPricing->setPrice(0);
            $channelPricing->setOriginalPrice(0);
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

            if ($object->isTracked()) {
                if ($object->getUom() === null) {
                    $errorElement
                        ->with('uom')
                            ->addViolation('sil.product_variant_uom.not_null')
                        ->end();
                }
                if ($object->getOutputStrategy() === null) {
                    $errorElement
                        ->with('outputStrategy')
                            ->addViolation('sil.product_variant_outputStrategy.not_null')
                        ->end();
                }
            }
        }
    }

    public function getQtyByItemAndLocation(StockItemInterface $item, Location $location)
    {
        return $this->getStockItemQueries()->getQtyByLocation($item, $location);
    }

    public function getUnitsByItemAndLocation(StockItemInterface $item, Location $location)
    {
        return $this->getStockUnitRepository()->findByStockItemAndLocation($item, $location);
    }

    public function getLocationsByItem(StockItemInterface $item)
    {
        return $this->getLocationRepository()->findByOwnedItem($item, LocationType::INTERNAL);
    }

    public function getInStockQty(StockItemInterface $item)
    {
        return $this->getStockItemQueries()->getQty($item);
    }

    public function getInStockQtyByLocation(StockItemInterface $item, Location $location)
    {
        return $this->getStockItemQueries()->getQtyByLocation($item, $location);
    }

    public function getReservedQty(StockItemInterface $item)
    {
        return $this->getStockItemQueries()->getReservedQty($item);
    }

    public function getAvailableQty(StockItemInterface $item)
    {
        return $this->getStockItemQueries()->getAvailableQty($item);
    }

    public function getLocationRepository(): LocationRepositoryInterface
    {
        return $this->locationRepository;
    }

    public function setLocationRepository(LocationRepositoryInterface $stockUnitRepository)
    {
        $this->locationRepository = $stockUnitRepository;
    }

    public function getStockUnitRepository(): StockUnitRepositoryInterface
    {
        return $this->stockUnitRepository;
    }

    public function setStockUnitRepository(StockUnitRepositoryInterface $stockUnitRepository)
    {
        $this->stockUnitRepository = $stockUnitRepository;
    }

    public function getStockItemQueries(): StockItemQueriesInterface
    {
        return $this->stockItemQueries;
    }

    public function setStockItemQueries(StockItemQueriesInterface $stockItemQueries)
    {
        $this->stockItemQueries = $stockItemQueries;
    }

    public function toString($item)
    {
        return sprintf('[%s] %s', $item->getCode(), $item->getName());
    }
}
