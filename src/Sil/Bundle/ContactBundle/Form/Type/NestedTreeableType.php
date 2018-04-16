<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Form\Type;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Blast\Bundle\CoreBundle\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class NestedTreeableType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $queryBuilder = function (Options $options) {
            return $options['em']
                ->getRepository($options['class'])
                ->getNodesHierarchyQueryBuilder();
        };

        $choiceLabel = function ($choice) {
            return str_repeat(' - ', $choice->getTreeLvl()) . $choice->getName();
        };

        $resolver->setDefaults([
            'query_builder' => $queryBuilder,
            'choice_label'  => $choiceLabel,
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        if ($form->getData()) {
            $object_id = ($view->vars['name'] == 'parent') ? $form->getParent()->getData()->getId() : null;

            $choices = [];

            foreach ($view->vars['choices'] as $choice) {
                if ($object_id && $choice->data->getId() == $object_id) {
                    $choice->attr['disabled'] = 'disabled';
                }

                $choices[] = $choice;
            }

            $view->vars['choices'] = $choices;
        }
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
