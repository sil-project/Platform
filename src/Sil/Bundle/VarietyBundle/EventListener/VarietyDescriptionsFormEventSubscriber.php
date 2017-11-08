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

namespace Sil\Bundle\VarietyBundle\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Blast\Bundle\CoreBundle\Admin\CoreAdmin;
use Doctrine\Common\Collections\ArrayCollection;

class VarietyDescriptionsFormEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var CoreAdmin
     */
    private $admin;

    /**
     * @var array
     */
    private $config;

    public function __construct(CoreAdmin $admin, array $config)
    {
        $this->admin = $admin;
        $this->config = $config;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            // FormEvents::POST_SUBMIT => 'postSubmit',
        ];
    }

    public function preSetData(FormEvent $event)
    {
        $this->buildVarietyDescriptionsForm($event, FormEvents::PRE_SET_DATA);
    }

    public function postSubmit(FormEvent $event)
    {
        $this->buildVarietyDescriptionsForm($event, FormEvents::POST_SUBMIT);
    }

    public function buildVarietyDescriptionsForm(FormEvent $event, $eventType)
    {
        $form = $event->getForm();
        $subject = $this->admin->getSubject($event->getData());

        if ($eventType === FormEvents::PRE_SET_DATA) {
            foreach ($this->config as $fieldset => $field) {
                $getter = 'get' . ucfirst($fieldset) . 'Descriptions';
                $setter = 'set' . ucfirst($fieldset) . 'Descriptions';
                $remover = 'remove' . ucfirst($fieldset) . 'Description';
                $adder = 'add' . ucfirst($fieldset) . 'Description';
                $constructor = '\Sil\Bundle\VarietyBundle\Entity\VarietyDescription' . ucfirst($fieldset);

                // Hide VarietyDescriptions that are not found in configuration
                foreach ($subject->$getter() as $desc) {
                    $found = false;
                    foreach ($this->config[$fieldset] as $field => $settings) {
                        if ($desc->getField() == $field) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $subject->$remover($desc);
                    }
                }

                // Create missing VarietyDescriptions (described in configuration and not present in the Variety)
                foreach ($this->config[$fieldset] as $field => $settings) {
                    $exists = $subject->$getter()->exists(function ($key, $element) use ($field) {
                        return $element->getField() == $field;
                    });
                    if (!$exists) {
                        $desc = new $constructor();
                        $desc->setFieldset($fieldset);
                        $desc->setField($field);
                        $subject->$adder($desc);
                    }
                }

                $this->sortDescriptions($this->config, $fieldset, $subject, $getter, $setter);
            }
        }
    }

    private function sortDescriptions($config, $fieldset, $subject, $getter, $setter)
    {
        // Sort VarietyDescriptions according to the configuration order
        $order = [];
        $i = 0;
        foreach ($config[$fieldset] as $field => $settings) {
            $order[$field] = $i++;
        }
        $array = iterator_to_array($subject->$getter()->getIterator());
        usort($array, function ($a, $b) use ($order) {
            return ($order[$a->getField()] < $order[$b->getField()]) ? -1 : 1;
        });
        $subject->$setter(new ArrayCollection($array));
    }
}
