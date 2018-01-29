<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UtilsBundle\Form\Type;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Blast\Bundle\CoreBundle\Form\AbstractType as BaseAbstractType;
use Blast\Bundle\UtilsBundle\Services\UniqueFieldChecker;

/**
 * Form type that checks unique values.
 */
class UniqueFieldType extends BaseAbstractType
{
    /**
     * @var UniqueFieldChecker
     */
    private $checker;

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blast_unique_field';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();

            $className = $form->getParent()->getConfig()->getDataClass();
            $field = $form->getConfig()->getName();
            $value = $event->getData();
            $result = $this->checker->check($className, $field, $value);

            $id = $form->getParent()->getData()->getId();

            if ($result['object'] && $result['object']->getId() !== $id && !$result['available']) {
                $form->addError(new FormError($this->checker->renderResult($result, $value)));
            }
        });
    }

    /**
     * @param UniqueFieldChecker $checker
     */
    public function setUniqueFieldChecker(UniqueFieldChecker $checker)
    {
        $this->checker = $checker;
    }
}
