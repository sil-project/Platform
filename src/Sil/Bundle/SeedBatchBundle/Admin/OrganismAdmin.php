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

namespace Sil\Bundle\SeedBatchBundle\Admin;

use Sil\Bundle\CRMBundle\Entity\Organism;
use Sil\Bundle\EmailCRMBundle\Admin\OrganismAdmin as BaseOrganismAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Validator\ErrorElement;

class OrganismAdmin extends BaseOrganismAdmin
{
    protected $baseRouteName = 'admin_sil_seedbatch_organism';
    protected $baseRoutePattern = 'sil/seedbatch/organism';

    /**
     * @param DatagridMapper $mapper
     */
    protected function configureDatagridFilters(DatagridMapper $mapper)
    {
        parent::configureDatagridFilters($mapper);
    }

    /**
     * @param ListMapper $mapper
     */
    protected function configureListFields(ListMapper $mapper)
    {
        parent::configureListFields($mapper);
    }

    /**
     * @param FormMapper $mapper
     */
    protected function configureFormFields(FormMapper $mapper)
    {
        parent::configureFormFields($mapper);

        if ($this->subject) {
            if (!$this->subject->isSeedProducer()) {
                $tabs = $mapper->getadmin()->getFormTabs();
                unset($tabs['form_tab_plots']);
                unset($tabs['form_tab_seedbatches']);
                $mapper->getAdmin()->setFormTabs($tabs);
                $mapper->remove('plots');
                $mapper->remove('seedBatches');
            }
        }
    }

    /**
     * @param ShowMapper $mapper
     */
    protected function configureShowFields(ShowMapper $mapper)
    {
        parent::configureShowFields($mapper);

        if ($this->subject) {
            if (!$this->subject->isSeedProducer()) {
                $tabs = $mapper->getadmin()->getShowTabs();
                unset($tabs['show_tab_plots']);
                unset($tabs['show_tab_seedbatches']);
                $mapper->getAdmin()->setShowTabs($tabs);
                $mapper->remove('plots');
                $mapper->remove('seedBatches');
            }
        }
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        parent::validate($errorElement, $object);
        $this->validateSeedProducerCode($errorElement, $object);
    }

    /**
     * Seed producer code validator.
     *
     * @param ErrorElement $errorElement
     * @param Organism     $object
     */
    public function validateSeedProducerCode(ErrorElement $errorElement, $object)
    {
        $code = $object->getSeedProducerCode();
        $container = $this->getConfigurationPool()->getContainer();
        $is_new = empty($object->getId());

        if (empty($code)) {
            // Check if organism is a seed producer (belongs to the seed_producers app circle)
            $app_circles = $container->get('sil_crm.app_circles');
            if ($app_circles->isInCircle($object, 'seed_producers') && !$is_new) {
                $errorElement
                    ->with('seedProducerCode')
                        ->addViolation('A seed producer code is required for seed producers')
                    ->end()
                ;
            }
        } else {
            $registry = $container->get('blast_core.code_generators');
            $codeGenerator = $registry->getCodeGenerator(Organism::class, 'seedProducerCode');
            if (!$codeGenerator->validate($code)) {
                $errorElement
                    ->with('seedProducerCode')
                        ->addViolation('Wrong format for seed producer code. It shoud be: ' . $codeGenerator::getHelp())
                    ->end()
                ;
            }
        }
    }
}
