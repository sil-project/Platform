<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Form\Type;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Doctrine\ORM\EntityManager;
use Blast\Bundle\CoreBundle\Form\AbstractType;

class TreeableType extends AbstractType
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $choices = [];

        $object_id = ($view->vars['name'] == 'parentNode') ? $form->getParent()->getData()->getId() : null;

        foreach ($view->vars['choices'] as $choice) {
            $choice->attr['data-node-level'] = $choice->data->getNodeLevel();

            if ($object_id && $choice->data->getId() == $object_id) {
                $choice->attr['disabled'] = 'disabled';
            }

            if ($choice->data->isRootNode()) {
                $admin = $this->getAdmin($options);
                $choice->label = $admin->trans('parent_root_node_label');
            }

            $choices[] = $choice;
        }

        $view->vars['choices'] = $choices;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $queryBuilder = function (Options $options) {
            $min = $options['min_node_level'];
            $max = $options['max_node_level'];

            return $options['em']
                    ->getRepository($options['class'])
                    ->createOrderedQB($min, $max);
        };

        $choiceLabel = function ($choice) {
            $level = $choice->getNodeLevel() - 1;

            return str_repeat('- - ', $level) . (string) $choice;
        };

        $queryBuilderNormalizer = function (Options $options, $qb) {
            if (is_callable($qb)) {
                $qb = call_user_func($qb, $options['em']->getRepository($options['class']));

                if (!$qb instanceof QueryBuilder) {
                    throw new UnexpectedTypeException($qb, 'Doctrine\ORM\QueryBuilder');
                }
            }

            return $qb;
        };

        $resolver->setNormalizer('query_builder', $queryBuilderNormalizer);
        $resolver->setDefaults(array(
            'min_node_level' => 0,
            'max_node_level' => 0,
            'choice_label'   => $choiceLabel,
            'query_builder'  => $queryBuilder,
        ));
    }

    /**
     * @param array $options
     *
     * @return FieldDescriptionInterface
     *
     * @throws \RuntimeException
     */
    protected function getFieldDescription(array $options)
    {
        if (!isset($options['sonata_field_description'])) {
            throw new \RuntimeException('Please provide a valid `sonata_field_description` option');
        }

        return $options['sonata_field_description'];
    }

    /**
     * @param array $options
     *
     * @return AdminInterface
     */
    protected function getAdmin(array $options)
    {
        return $this->getFieldDescription($options)->getAdmin();
    }

    public function getParent()
    {
        return 'entity';
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getBlockPrefix()
    {
        return 'blast_treeable';
    }
}
