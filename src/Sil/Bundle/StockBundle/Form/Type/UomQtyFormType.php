<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Sil\Bundle\StockBundle\Domain\Repository\UomRepositoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Sil\Bundle\StockBundle\Domain\Entity\StockItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormInterface;
use Sil\Bundle\StockBundle\Domain\Entity\UomQty;

/**
 * Description of UomQtyType.
 *
 * @author glenn
 */
class UomQtyFormType extends BaseType
{
    /**
     * @var UomRepositoryInterface
     */
    protected $uomRepository;

    /**
     * @param UomRepositoryInterface $manager
     */
    public function __construct(UomRepositoryInterface $uomRepository)
    {
        $this->uomRepository = $uomRepository;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'attr'       => ['class' => 'form-inline'],
            'data_class' => 'Sil\Bundle\StockBundle\Domain\Entity\UomQty',
            'empty_data' => function (FormInterface $form) {
                return new UomQty(
                    $form->get('uom')->getData(),
                    floatval($form->get('value')->getData())
                );
            },
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            [$this, 'buildUomTypeChoices']);

        $builder->add('value', NumberType::class,
            [
                'required' => true,
                'attr'     => [
                    'placeholder' => 'Quantité', ],
                'label' => false, ]);
    }

    public function buildUomTypeChoices(FormEvent $event)
    {
        $uomQty = $event->getData();
        $form = $event->getForm();
        $movementData = $form->getParent()->getData();
        $choices = [];
        $uoms = null;

        if (null !== $movementData) {
            /* @var $stockItem StockItem */
            $stockItem = $movementData->getStockItem();
            if (null !== $stockItem) {
                $uoms = $stockItem->getUom()->getType()->getUoms();
            }
        }

        if (null == $uoms) {
            $uoms = $this->uomRepository->findAll();
        }

        foreach ($uoms as $uom) {
            $choices[$uom->getName()] = $uom->getId();
        }

        $form->add('uom', EntityType::class,
            [
                'label'        => false,
                'class'        => 'Sil\Bundle\StockBundle\Domain\Entity\Uom',
                'choices'      => $uoms,
                'choice_label' => 'name',
        ]);
    }

    public function getBlockPrefix()
    {
        return self::class;
    }
}
