<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ChannelAdmin extends SyliusGenericAdmin
{
    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.ecommerce.channel';

    /**
     * @param FormMapper $mapper
     */
    protected function configureFormFields(FormMapper $mapper)
    {
        parent::configureFormFields($mapper);

        $syliusThemeConfig = $this->getConfigurationPool()->getContainer()->get('sylius.theme.configuration.provider')->getConfigurations();
        $listOfThemes = [
            'default' => 'Default Sylius theme',
        ];
        foreach ($syliusThemeConfig as $k => $conf) {
            $listOfThemes[$conf['name']] = $conf['title'];
        }

        $groups = $this->getFormGroups();

        $mapper->remove('taxCalculationStrategy');
        $mapper->add(
            'taxCalculationStrategy',
            ChoiceType::class,
            [
                'label'    => 'sil.label.taxCalculationStrategy',
                'choices'  => array_flip($this->getConfigurationPool()->getContainer()->getParameter('sylius.taxation.calculation_strategy.list_values')),
                'required' => true,
                'attr'     => [
                    'class' => 'inline-block',
                    'width' => 50,
                ],
            ]
        );

        $mapper->remove('themeName');
        $mapper->add(
            'themeName',
            ChoiceType::class,
            [
                'label'    => 'sil.label.themeName',
                'choices'  => array_flip($listOfThemes),
                'required' => true,
                'attr'     => [
                    'class'=> 'inline-block',
                    'width'=> 50,
                ],
            ]
        );

        $tabs = $this->getFormTabs();
        unset($tabs['default']);
        $this->setFormTabs($tabs);

        $this->setFormGroups($groups);
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        if ($object) {
            $id = $object->getId();
            $code = $object->getCode();
            $name = $object->getName();

            $qb = $this->getModelManager()->createQuery(get_class($object), 'c');

            if ($id !== null) {
                $qb
                    ->where('c.id <> :currentId')
                    ->setParameter('currentId', $id);
            } else {
                $qb
                    ->where('c.id IS NOT NULL');
            }

            $qbCode = clone $qb;
            $qbName = clone $qb;
            unset($qb);

            // CHECK CODE UNICITY

            $qbCode
                ->andWhere('c.code = :currentCode')
                ->setParameter('currentCode', $code);

            if (count($qbCode->getQuery()->getResult()) != 0) {
                $errorElement
                    ->with('code')
                    ->addViolation('sil.channel_code.not_unique', ['%code%' => $code])
                    ->end();
            }

            // CHECK NAME UNICITY

            $qbName
                ->andWhere('c.name = :currentName')
                ->setParameter('currentName', $name);

            if (count($qbName->getQuery()->getResult()) != 0) {
                $errorElement
                    ->with('name')
                    ->addViolation('sil.channel_name.not_unique', ['%name%' => $name])
                    ->end();
            }
        }
    }
}
