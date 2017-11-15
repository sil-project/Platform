<?php

/*
 * This file is part of the Lisem Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Admin;

use Sil\Bundle\EcommerceBundle\Admin\ProductAdmin;
use Sil\Bundle\VarietyBundle\Entity\Variety;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DatagridBundle\ProxyQuery\ProxyQueryInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Sonata admin for seeds products.
 *
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class SeedsProductAdmin extends ProductAdmin
{
    protected $baseRouteName = 'admin_sil_seeds_product';
    protected $baseRoutePattern = 'sil/seeds_product';
    protected $classnameLabel = 'SeedsProduct';

    /**
     * @var Variety
     */
    private $variety;

    public function configureFormFields(FormMapper $mapper)
    {
        $variety = $this->getVariety();
        $request = $this->getRequest();

        $basicForm = false;
        if (null !== $request->get('btn_create_for_variety')) {
            $basicForm = true;
        } elseif ($request->getMethod() == 'GET' && !$request->get($this->getIdParameter()) && !$variety) {
            $basicForm = true;
        }

        parent::configureFormFields($mapper);

        if ($basicForm) {
            // First step creation form with just the Variety field
            $mapper
                ->tab('form_tab_general')
                    ->with('form_group_general')
                        ->add(
                            'variety',
                            'sonata_type_model_autocomplete',
                            [
                                'property'    => ['name', 'code'],
                                'required'    => true,
                                'constraints' => [new NotBlank()],
                                'attr'        => [
                                    'class' => 'inline-block',
                                    'width' => 50,
                                ],
                            ],
                            [
                                'admin_code' => 'lisem.admin.variety',
                            ]
                        )
                    ->end()
                ->end()
            ;

            $this->removeTab(['form_tab_variants', 'form_tab_images'], $mapper);
            // return;
        }

        // Regular edit/create form
        // parent::configureFormFields($mapper);
    }

    /**
     * @param string $context NEXT_MAJOR: remove this argument
     *
     * @return ProxyQueryInterface
     */
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $alias = $query->getRootAliases()[0];
        $query->andWhere(
            $query->expr()->isNotNull("$alias.variety")
        );

        return $query;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->remove('delete');
    }

    /**
     * @return ProductInterface
     */
    public function getNewInstance()
    {
        $factory = $this->getConfigurationPool()->getContainer()->get('sylius.factory.product');
        $object = $this->getVariety() ?
            $factory->createNewForVariety($this->getVariety()) :
            $factory->createNew(true)
        ;

        foreach ($this->getExtensions() as $extension) {
            $extension->alterNewInstance($this, $object);
        }

        return $object;
    }

    /**
     * @return Variety|null
     *
     * @throws \Exception
     */
    public function getVariety()
    {
        if ($this->variety) {
            return $this->variety;
        }

        if ($this->subject && $variety = $this->subject->getVariety()) {
            $this->variety = $variety;

            return $variety;
        }

        if ($variety_id = $this->getRequest()->get('variety_id')) {
            $variety = $this->modelManager
                ->getEntityManager('SilVarietyBundle:Variety')
                ->getRepository('SilVarietyBundle:Variety')
                ->find($variety_id);
            if (!$variety) {
                throw new \Exception(sprintf('Unable to find Variety with id : %s', $variety_id));
            }
            $this->variety = $variety;

            return $variety;
        }

        return null;
    }

    public function SonataTypeModelAutocompleteCallback($admin, $property, $value)
    {
        $datagrid = $admin->getDatagrid();
        $qb = $datagrid->getQuery();
        $alias = $qb->getRootAlias();
        $qb
            ->leftJoin("$alias.translations", 'translations')
            ->andWhere($qb->expr()->orX(
                'translations.name LIKE :value',
                $alias . '.code LIKE :value'
            ))
            ->setParameter('value', "%$value%")
        ;
    }
}
