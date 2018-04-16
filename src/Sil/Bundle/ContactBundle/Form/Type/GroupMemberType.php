<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * Group member form.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class GroupMemberType extends AbstractType
{
    /**
     * Group model FQCN.
     *
     * @var string
     */
    private $modelClass;

    /**
     * @param ArrayToGroupTransformer $transformer
     */
    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('group', NestedTreeableType::class, [
                'label' => 'sil_contact.group_member.group',
                'class' => $this->modelClass,
            ])

            ->add('member', HiddenType::class)

            ->add('save', SubmitType::class, ['label' => 'sil_contact.form.save'])
        ;
    }
}
