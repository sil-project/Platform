<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PlatformBundle\Admin;

use Blast\Bundle\CoreBundle\Admin\Traits\HandlesRelationsAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Blast\Bundle\ResourceBundle\Sonata\Admin\ResourceAdmin;
use Sil\Component\Stock\Query\StockItemQueriesInterface;
use Sil\Component\Stock\Repository\LocationRepositoryInterface;
use Sil\Component\Stock\Repository\StockUnitRepositoryInterface;
use Sil\Component\Stock\Model\StockItemInterface;
use Sil\Component\Stock\Model\Location;
use Sil\Component\Stock\Model\LocationType;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductAdmin extends ResourceAdmin
{
    use HandlesRelationsAdmin {
        configureFormFields as configFormHandlesRelations;
        configureShowFields as configShowHandlesRelations;
    }

    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.product';

    protected $baseRouteName = 'admin_ecommerce_product';
    protected $baseRoutePattern = 'product';

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

    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);

        if ($action === 'list') {
            $list = array_merge(
                $list,
                [
                    ['template' => 'SilEcommerceBundle:CRUD:list__action_shop_link.html.twig'],
                ]
            );
        } elseif ($action === 'edit') {
            $list = array_merge(
                $list,
                [
                    ['template' => 'SilEcommerceBundle:CRUD:global__action_shop_link.html.twig'],
                ]
            );
        }

        return $list;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->add('generateProductSlug', 'generate_product_slug');
        $collection->add('setAsCoverImage', 'setAsCoverImage');
        $collection->remove('delete');
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);

        return $actions;
    }

    /**
     * Configure create/edit form fields.
     *
     * @param FormMapper
     */
    protected function configureFormFields(FormMapper $mapper)
    {
        parent::configureFormFields($mapper);
        //calls to methods of traits
        $this->configFormHandlesRelations($mapper);
        $admin = $this;
        $mapper->getFormBuilder()->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($admin) {
                $form = $event->getForm();
                $formData = $event->getData();
                $subject = $admin->getSubject($formData);

                // Avoid variants submit (because it is already managed in ajax)
                if ($form->has('variants')) {
                    $form->remove('variants');
                }
            }
        );

        $subject = $this->getSubject();

        if ($subject) {
            if ($subject->getId() === null) {
                $tabs = $mapper->getadmin()->getFormTabs();
                unset($tabs['form_tab_variants']);
                unset($tabs['form_tab_images']);
                $mapper->getAdmin()->setFormTabs($tabs);
                $mapper->remove('variants');
                $mapper->remove('images');
            }
        }
    }

    /**
     * Configure Show view fields.
     *
     * @param ShowMapper $mapper
     */
    protected function configureShowFields(ShowMapper $mapper)
    {
        // call to aliased trait method
        $this->configShowHandlesRelations($mapper);
    }

    public function prePersist($product)
    {
        parent::prePersist($product);
        $this->handleProductTaxonCollection($product);

        $slugGenerator = $this->getConfigurationPool()->getContainer()->get('sylius.generator.slug');
        $product->setSlug($slugGenerator->generate($product->getName()));
    }

    public function preUpdate($product)
    {
        parent::preUpdate($product);
        $this->handleProductTaxonCollection($product);
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        if ($object) {
            $id = $object->getId();
            $code = $object->getCode();

            $qb = $this->getModelManager()->createQuery(get_class($object), 'p');

            $qb
                ->where('p.id <> :currentId')
                ->andWhere('p.code = :currentCode')
                ->setParameters(
                    [
                        'currentId'   => $id,
                        'currentCode' => $code,
                    ]
                );

            if (count($qb->getQuery()->getResult()) != 0) {
                $errorElement
                    ->with('code')
                    ->addViolation('sil.product_code.not_unique')
                    ->end();
            }
        }
    }

    private function handleProductTaxonCollection($product)
    {
        $taxons = $product->getTaxons();

        $exisingProductTaxons = $product->getProductTaxons();

        $productTaxonClass = $this->getConfigurationPool()->getContainer()->getParameter('sylius.model.product_taxon.class');

        foreach ($exisingProductTaxons as $exisingPTaxon) {
            if (!$taxons->contains($exisingPTaxon->getTaxon())) {
                $exisingProductTaxons->removeElement($exisingPTaxon);
            } else {
                $taxons->removeElement($exisingPTaxon->getTaxon());
            }
        }

        foreach ($taxons as $taxon) {
            $pTaxon = new $productTaxonClass();
            $pTaxon->setProduct($product);
            $pTaxon->setTaxon($taxon);

            $product->addProductTaxon($pTaxon);
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
