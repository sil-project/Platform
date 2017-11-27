<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\VarietyBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Blast\Bundle\CoreBundle\Admin\CoreAdmin;
use Blast\Bundle\CoreBundle\Admin\Traits\HandlesRelationsAdmin;
use Sil\Bundle\VarietyBundle\Traits\DynamicDescriptions;

class VarietyAdmin extends CoreAdmin
{
    use HandlesRelationsAdmin {
        configureFormFields as configFormHandlesRelations;
        configureShowFields as configShowHandlesRelations;
    }

    use DynamicDescriptions;

    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.variety.variety';

    /**
     * Configure routes for list actions.
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->add('strain', $this->getRouterIdParameter() . '/strain');
        $collection->add('getFilterWidget', 'getFilterWidget/{fieldset}/{field}');
        $collection->add('hierarchy', 'hierarchy/{id}');
    }

    /**
     * @return array
     */
    public function getFormTheme()
    {
        return array_merge(
            parent::getFormTheme(),
            array('SilVarietyBundle:VarietyAdmin:form_theme.html.twig')
        );
    }

    /**
     * Configure create/edit form fields.
     *
     * @param FormMapper
     */
    protected function configureFormFields(FormMapper $mapper)
    {
        //calls to methods of traits
        $this->configFormHandlesRelations($mapper);
        $this->configureDynamicDescriptions($mapper);
    }

    /**
     * Configure Show view fields.
     *
     * @param ShowMapper $mapper
     */
    protected function configureShowFields(ShowMapper $mapper)
    {
        // call to aliased trait method
        $this->configShowHandlesRelations($mapper);
        $this->configureShowDescriptions($mapper);

        //Removal of Variety/Strain specific fields
        if ($this->getSubject()) {
            if ($this->getSubject()->getIsStrain()) {
                $tabs = $mapper->getadmin()->getShowTabs();
                unset($tabs['form_tab_strains']);
                $mapper->getAdmin()->setShowTabs($tabs);
                $mapper->remove('children');
                $mapper->remove('several_strains');
            } else {
                $mapper->remove('parent');
            }
        }
    }

    //prevent primary key loop
    public function prePersist($variety)
    {
        parent::prePersist($variety);

        if ($variety->getParent()) {
            if ($variety->getParent()->getId() == $variety->getId()) {
                $variety->setParent(null);
            }
        }
    }

    public function getExportFields()
    {
        return [
            'name'           => 'name',
            'latin_name'     => 'latin_name',
            'code'           => 'code',
            'species.name'   => 'species.name',
            'parent.name'    => 'parent.name',
            'parent.code'    => 'parent.code',
            'alias'          => 'alias',
        ];
    }
}
