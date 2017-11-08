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

namespace Sil\Bundle\VarietyBundle\Form\ChoiceLoader;

use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\ChoiceList\ChoiceListInterface;
use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Doctrine\ORM\EntityManager;

class VarietyDescriptionChoiceLoader implements ChoiceLoaderInterface
{
    /** @var ChoiceListInterface */
    private $choiceList;

    /** @var EntityRepository */
    private $repository;

    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var array
     */
    private $options;

    /**
     * @param EntityManager $manager
     * @param array         $options
     */
    public function __construct(EntityManager $manager, $options)
    {
        $this->options = $options;
        $this->manager = $manager;
    }

    public function loadValuesForChoices(array $choices, $value = null)
    {
        $values = array();
        foreach ($choices as $key => $choice) {
            if (is_callable($value)) {
                $values[$key] = (string) call_user_func($value, $choice, $key);
            } else {
                $values[$key] = $choice;
            }
        }
        $this->choiceList = new ArrayChoiceList($values, $value);

        return $values;
    }

    public function loadChoiceList($value = null)
    {
        $choiceList = [];
        $fieldSet = ucfirst($this->options['fieldset']);
        $qb = $this->manager->getRepository(sprintf('SilVarietyBundle:VarietyDescription%s', $fieldSet))->createQueryBuilder('v');

        $choices = $qb->select('v.field')
                ->distinct(true)
                ->getQuery()
                ->getResult()
        ;

        foreach ($choices as $choice) {
            $field = $choice['field'];
            $choiceList[$field] = $field;
        }
        $this->choiceList = new ArrayChoiceList($choiceList, $value);

        return $this->choiceList;
    }

    public function loadChoicesForValues(array $values, $value = null)
    {
        $choices = array();
        foreach ($values as $key => $val) {
            if (is_callable($value)) {
                $choices[$key] = (string) call_user_func($value, $val, $key);
            } else {
                $choices[$key] = $val;
            }
        }
        $this->choiceList = new ArrayChoiceList($values, $value);

        return $choices;
    }
}
