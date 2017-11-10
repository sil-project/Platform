<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Form\Type;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Blast\Bundle\CoreBundle\Form\AbstractType;

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
            return str_repeat(' - ', $choice->getTreeLvl()) . (string) $choice;
        };

        $resolver->setDefaults([
            'query_builder' => $queryBuilder,
            'choice_label'  => $choiceLabel,
            'btn_add'       => 'link_add',
            'btn_list'      => 'link_list',
            'btn_delete'    => 'link_delete',
            'btn_catalogue' => 'SonataAdminBundle',
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $object_id = ($view->vars['name'] == 'treeParent') ? $form->getParent()->getData()->getId() : null;

        $choices = [];

        foreach ($view->vars['choices'] as $choice) {
            if ($object_id && $choice->data->getId() == $object_id) {
                $choice->attr['disabled'] = 'disabled';
            }

            $choices[] = $choice;
        }

        $view->vars['choices'] = $choices;
        $view->vars['btn_add'] = $options['btn_add'];
        $view->vars['btn_list'] = $options['btn_list'];
        $view->vars['btn_delete'] = $options['btn_delete'];
        $view->vars['btn_catalogue'] = $options['btn_catalogue'];
    }

    public function getParent()
    {
        return 'entity';
    }

    public function getBlockPrefix()
    {
        return 'blast_nested_treeable';
    }
}
