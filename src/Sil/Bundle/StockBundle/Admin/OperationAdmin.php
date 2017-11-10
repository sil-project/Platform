<?php

declare(strict_types=1);

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Admin;

use Blast\Bundle\ResourceBundle\Sonata\Admin\ResourceAdmin;
use Sil\Bundle\StockBundle\Domain\Generator\OperationCodeGeneratorInterface;
use Sil\Bundle\StockBundle\Domain\Generator\MovementCodeGeneratorInterface;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Form\FormMapper;
use Sil\Bundle\StockBundle\Domain\Repository\OperationTypeRepositoryInterface;
use Sil\Bundle\StockBundle\Domain\Repository\LocationRepositoryInterface;
use Sil\Bundle\StockBundle\Domain\Repository\PartnerRepositoryInterface;
use Sil\Bundle\StockBundle\Domain\Entity\OperationType;
use Sil\Bundle\StockBundle\Form\DataTransformer\OperationTypeDataTransformer;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class OperationAdmin extends ResourceAdmin
{
    protected $baseRouteName = 'admin_stock_operation';
    protected $baseRoutePattern = 'stock/operation';

    /**
     * @var MovementCodeGeneratorInterface
     */
    protected $movementCodeGenerator;

    /**
     * @var OperationCodeGeneratorInterface
     */
    protected $operationCodeGenerator;

    /**
     * @var OperationTypeRepositoryInterface
     */
    protected $operationTypeRepository;

    /**
     * @var LocationRepositoryInterface
     */
    protected $locationRepository;

    /**
     * @var PartnerRepositoryInterface
     */
    protected $partnerRepository;

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('cancel', $this->getRouterIdParameter() . '/cancel');
        $collection->add('confirm', $this->getRouterIdParameter() . '/confirm');
        $collection->add('reserve', $this->getRouterIdParameter() . '/reserve');
        $collection->add('unreserve',
            $this->getRouterIdParameter() . '/unreserve');
        $collection->add('apply', $this->getRouterIdParameter() . '/apply');

        $collection->add('create_by_type', 'create/{type}');
    }

    /**
     * {@inheritdoc}
     */
    public function configureFormFields(FormMapper $mapper)
    {
        parent::configureFormFields($mapper);
        $type = $this->getSubject()->getType();

        $generalTab = $mapper->tab('sil.stock.operation.form.tab.general');
        $descGroup = $generalTab->with('sil.stock.operation.form.group.description');

        $this->createOperationTypeFields($descGroup, $type, $mapper);

        $descGroup->add('expectedAt', 'sonata_type_datetime_picker',
            ['required' => true]);

        if (!$type->isInternalTransfer()) {
            $this->createPartnerField($descGroup, $type, $mapper);
        }
        $descGroup->end();

        $locationGroup = $generalTab->with('sil.stock.operation.form.group.locations');
        $this->createLocationFields($locationGroup, $type, $mapper);
    }

    /**
     * @param type          $group
     * @param OperationType $type
     * @param FormMapper    $mapper
     */
    private function createOperationTypeFields($group, OperationType $type,
        FormMapper $mapper)
    {
        $group->add('type_choice', 'choice',
            [
                'label'        => 'type',
                'mapped'       => false,
                'choices'      => OperationType::getTypes(),
                'choice_label' => function ($label) {
                    return 'sil.stock.operation_type.' . $label;
                },
                'choice_value' => 'value',
                'data'         => $type,
                'attr'         => ['disabled' => true],
        ]);
        $group->add('type', 'hidden');
        $group->get('type')->addModelTransformer(new OperationTypeDataTransformer());
    }

    /**
     * @param type          $group
     * @param OperationType $type
     * @param FormMapper    $mapper
     */
    protected function createPartnerField($group, OperationType $type,
        FormMapper $mapper)
    {
        if ($type->isReceipt()) {
            $parters = $this->getPartnerRepository()->getSuppliers();
        } else {
            $parters = $this->getPartnerRepository()->getCustomers();
        }

        $group->add('partner', 'choice',
            [
                'choices'      => $parters,
                'choice_label' => 'fulltextName',
        ]);
    }

    /**
     * @param type          $group
     * @param OperationType $type
     * @param FormMapper    $mapper
     */
    private function createLocationFields($group, OperationType $type,
        FormMapper $mapper)
    {
        $srcLocations = [];
        $destLocations = [];

        if ($type->isReceipt()) {
            $srcLocations = $this->getLocationRepository()->findSupplierLocations();
            $destLocations = $this->getLocationRepository()->findInternalLocations();
        } elseif ($type->isShipping()) {
            $srcLocations = $this->getLocationRepository()->findInternalLocations();
            $destLocations = $this->getLocationRepository()->findCustomerLocations();
        } else {
            $destLocations = $srcLocations = $this->getLocationRepository()->findInternalLocations();
        }

        $group->add('srcLocation', 'choice',
            [
                'choices'      => $srcLocations,
                'choice_label' => 'name',
        ]);

        $group->add('destLocation', 'choice',
            [
                'choices'      => $destLocations,
                'choice_label' => 'name',
        ]);
    }

    /**
     * @return array|OperationType[]
     */
    public function getOperationTypes()
    {
        return OperationType::getTypes();
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($operation)
    {
        $this->preUpdate($operation);
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        //generate code for the operation and the related movements
        $code = $this->getOperationCodeGenerator()->generate();
        $object->setCode($code);

        foreach ($object->getMovements() as $m) {
            $mCode = $this->getMovementCodeGenerator()->generate(
                $m->getStockItem(), $m->getQty());
            $m->setCode($mCode);
        }

        parent::preUpdate($object);
    }

    /**
     * @return OperationCodeGeneratorInterface
     */
    public function getOperationCodeGenerator(): OperationCodeGeneratorInterface
    {
        return $this->operationCodeGenerator;
    }

    /**
     * @param OperationCodeGeneratorInterface $operationCodeGenerator
     */
    public function setOperationCodeGenerator(OperationCodeGeneratorInterface $operationCodeGenerator)
    {
        $this->operationCodeGenerator = $operationCodeGenerator;
    }

    /**
     * @return MovementCodeGeneratorInterface
     */
    public function getMovementCodeGenerator(): MovementCodeGeneratorInterface
    {
        return $this->movementCodeGenerator;
    }

    /**
     * @param MovementCodeGeneratorInterface $codeGenerator
     */
    public function setMovementCodeGenerator(MovementCodeGeneratorInterface $codeGenerator)
    {
        $this->movementCodeGenerator = $codeGenerator;
    }

    /**
     * @return LocationRepositoryInterface
     */
    public function getLocationRepository(): LocationRepositoryInterface
    {
        return $this->locationRepository;
    }

    /**
     * @param LocationRepositoryInterface $locationRepository
     */
    public function setLocationRepository(LocationRepositoryInterface $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * @return PartnerRepositoryInterface
     */
    public function getPartnerRepository(): PartnerRepositoryInterface
    {
        return $this->partnerRepository;
    }

    /**
     * @param PartnerRepositoryInterface $partnerRepository
     */
    public function setPartnerRepository(PartnerRepositoryInterface $partnerRepository)
    {
        $this->partnerRepository = $partnerRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function toString($operation)
    {
        $tr = $this->getConfigurationPool()->getContainer()->get('translator');
        $type = $tr->trans('sil.stock.operation_type.' . $operation->getType());

        //return sprintf('[%s] %s', $operation->getCode(), $type);

        return 'plop';
    }
}
