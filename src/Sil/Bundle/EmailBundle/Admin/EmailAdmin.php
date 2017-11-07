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

namespace Librinfo\EmailBundle\Admin;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Form\FormMapper;
use Blast\CoreBundle\Admin\CoreAdmin;
use Blast\CoreBundle\Admin\Traits\HandlesRelationsAdmin;
use Html2Text\Html2Text;

class EmailAdmin extends CoreAdmin
{
    use HandlesRelationsAdmin { configureFormFields as configFormHandlesRelations; }

    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->add('send', $this->getRouterIdParameter() . '/send');
    }

    protected function configureFormFields(FormMapper $mapper)
    {
        $this->configFormHandlesRelations($mapper);

        $builder = $mapper->getFormBuilder();
        $factory = $builder->getFormFactory();
        $request = $this->getRequest();
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($request, $factory) {
            $form = $event->getForm();
            $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
            if (!empty($request->get('force_user')) && $user && method_exists($user, 'getEmail')) {
                $options = $form->get('field_from')->getConfig()->getOptions();
                $options['auto_initialize'] = false;
                $options['attr']['readonly'] = 'readonly';
                $form->remove('field_from');
                $form->add($factory->createNamed('field_from', 'text', null, $options));
            }
        });
    }

    /**
     * @param FormMapper $mapper
     */
    public function postConfigureFormFields(FormMapper $mapper)
    {
    }

    public function prePersist($email)
    {
        parent::prePersist($email);

        $email->setTemplate(null);

        $this->setText($email);
    }

    public function preUpdate($email)
    {
        parent::preUpdate($email);

        $email->setTemplate(null);

        $this->setText($email);
    }

    protected function setText($email)
    {
        $html2T = new Html2Text($email->getContent());

        $email->setTextContent($html2T->getText());
    }

    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        $object = parent::getNewInstance();

        if ($this->hasRequest()) {
            $force_user = $this->getRequest()->get('force_user');
            if ($force_user) {
                $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
                if ($user && method_exists($user, 'getEmail')) {
                    $object->setFieldFrom($user->getEmail());
                }
            }

            $recipients = $this->getRequest()->get('recipients', []);
            if (!is_array($recipients)) {
                $recipients = [$recipients];
            }

            $recipient_class = $this->getRequest()->get('recipient_class');
            $recipient_ids = $this->getRequest()->get('recipient_ids');
            if ($recipient_ids && $recipient_class) {
                $ids = is_array($recipient_ids) ? $recipient_ids : [$recipient_ids];
                $entities = $this->getModelManager()->findBy($recipient_class, ['id' => $ids]);
                $rc = new \ReflectionClass($recipient_class);
                $adder = 'add' . $rc->getShortName();
                foreach ($entities as $entity) {
                    $object->$adder($entity);
                    if ($entity->getEmail()) {
                        $recipients[] = $entity->getEmail();
                    }
                }
            }

            $object->setFieldTo(implode(', ', array_unique($recipients)));
        }

        return $object;
    }

    public function getPersistentParameters()
    {
        $parentParams = parent::getPersistentParameters();

        if (!$this->getRequest()) {
            return $parentParams;
        }

        $params = [];

        if ($from_admin = $this->getRequest()->get('from_admin')) {
            $params['from_admin'] = $from_admin;
        }
        if ($from_id = $this->getRequest()->get('from_id')) {
            $params['from_id'] = $from_id;
        }

        return array_merge($parentParams, $params);
    }
}
