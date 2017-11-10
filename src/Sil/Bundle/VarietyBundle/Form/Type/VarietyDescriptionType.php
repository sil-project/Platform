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

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Doctrine\ORM\EntityManager;
use Blast\Bundle\CoreBundle\Form\AbstractType as BaseAbstractType;
use Sil\Bundle\VarietyBundle\Form\ChoiceLoader\VarietyDescriptionChoiceLoader;

class VarietyDescriptionType extends BaseAbstractType
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
        return 'sil_variety_description';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $manager = $this->manager;

        $choiceLoader = function (Options $options) use ($manager) {
            return new VarietyDescriptionChoiceLoader($manager, $options);
        };

        $resolver->setDefaults([
            'placeholder'   => '',
            'choice_loader' => $choiceLoader,
        ]);

        $resolver->setDefined('fieldset');
        $resolver->setDefined('field');
    }
}
