<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\VarietyBundle\Form\Type;

use Blast\Bundle\CoreBundle\Form\AbstractType as BaseAbstractType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VarietyDescriptionFilterType extends BaseAbstractType
{
    /** @var EntityManager */
    private $manager;

    /**
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fieldSet = explode('_', $options['fieldset'])[0];

        $silOptions = array(
            'fieldset' => $fieldSet,
            'required' => false,
        );

        $builder->add('Field', VarietyDescriptionType::class, array_merge($silOptions, $options['field_options']));
        $builder->add('Value', 'text', array_merge(array('required' => false), $options['field_options']));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'placeholder'   => '',
            'field_options' => array(),
        ]);

        $resolver->setDefined('fieldset');
        $resolver->setDefined('field_type');
    }

    public function getBlockPrefix()
    {
        return 'sil_variety_description_filter';
    }
}
