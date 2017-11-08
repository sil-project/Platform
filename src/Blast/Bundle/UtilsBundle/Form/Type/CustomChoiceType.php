<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UtilsBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Doctrine\ORM\EntityManager;
use Blast\Bundle\CoreBundle\Form\AbstractType as BaseAbstractType;
use Blast\Bundle\UtilsBundle\Form\ChoiceLoader\CustomChoiceChoiceLoader;
use Blast\Bundle\UtilsBundle\Form\DataTransformer\MultipleChoiceTransformer;

class CustomChoiceType extends BaseAbstractType
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

    public function getParent()
    {
        return 'choice';
    }

    public function getBlockPrefix()
    {
        return 'blast_custom_choice';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $manager = $this->manager;
        $defaultClass = '\Blast\Bundle\UtilsBundle\Entity\SelectChoice';

        $choiceLoader = function (Options $options) use ($manager) {
            return new CustomChoiceChoiceLoader($manager, $options);
        };

        $resolver->setDefaults([
            'placeholder'   => '',
            'choices_class' => $defaultClass,
            'choice_loader' => $choiceLoader,
            'is_filter'     => false,
        ]);
        $resolver->setRequired(['choices_class', 'choices_field']);
        $resolver->setDefined('blast_choices');
        $resolver->setDefined('is_filter');
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['choices_class'] = $options['choices_class'];
        $view->vars['choices_field'] = $options['choices_field'];
        $view->vars['is_filter'] = $options['is_filter'];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['multiple'] == true) {
            $builder->addModelTransformer(new MultipleChoiceTransformer());
        }
    }
}
