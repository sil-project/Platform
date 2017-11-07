<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\VarietiesBundle\Traits;

use Sonata\AdminBundle\Form\FormMapper;
use Librinfo\VarietiesBundle\EventListener\VarietyDescriptionsFormEventSubscriber;

trait DynamicDescriptions
{
    /**
     * @param FormMapper $mapper
     */
    protected function configureDynamicDescriptions($formMapper)
    {
        // Manage dynamic descriptions according to configuration settings
        $config = $this->getConfigurationPool()->getContainer()->getParameter('librinfo_varieties')['variety_descriptions'];
        $admin = $this;

        $formMapper->getFormBuilder()->addEventSubscriber(new VarietyDescriptionsFormEventSubscriber($admin, $config));
    }

    protected function configureShowDescriptions($showMapper)
    {
        $config = $this->getConfigurationPool()->getContainer()->getParameter('librinfo_varieties')['variety_descriptions'];

        foreach ($config as $fieldset => $fields) {
            if (!$this->getSubject()) {
                return;
            }
            $subject = $this->getSubject();
            $getter = 'get' . ucfirst($fieldset) . 'Descriptions';
            $setter = 'set' . ucfirst($fieldset) . 'Descriptions';
            $tabs = $showMapper->getAdmin()->getShowTabs();
            $descs = $subject->$getter();

            //$this->sortDescriptions($config, $fieldset, $subject, $getter, $setter);

            $showMapper->remove($fieldset . '_descriptions');
            unset($tabs['form_tab_' . $fieldset]);
            $showMapper->tab('form_tab_' . $fieldset)
                    ->with('form_group_' . $fieldset)
            ;

            foreach ($descs as $key => $desc) {
                $field = $desc->getField();
                $name = $fieldset . '||' . $desc->getField();
                $type = 'text';
                $options = array();
                $options['label'] = sprintf('librinfo_description_%s_%s', $fieldset, $field);

                if (isset($fields[$field]) && isset($fields[$field]['show'])) {
                    if (isset($fields[$field]['show']['type'])) {
                        $type = $fields[$field]['show']['type'];
                    }
                    if (isset($fields[$field]['show']['template'])) {
                        $options['template'] = $fields[$field]['show']['template'];
                    }
                }

                $showMapper->add($name, $type, $options);
            }
            //end group then tab
            $showMapper->end()->end();
        }
    }
}
