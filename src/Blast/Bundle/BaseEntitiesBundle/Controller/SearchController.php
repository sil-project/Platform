<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Controller;

use Sonata\AdminBundle\Controller\CoreController;
use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SearchController extends CoreController
{
    /**
     * Retrieve list of items for autocomplete form field with search indexes
     * Based on Sonata\AdminBundle\Controller\HelperController#retrieveAutocompleteItemsAction.
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \RuntimeException
     * @throws AccessDeniedException
     *
     * @todo refactor this to avoid dependency to BaseEntitiesBundle
     */
    public function retrieveAutocompleteItemsAction(Request $request)
    {
        //        $$pool = $this->container->get('sonata.admin.pool')->getInstance($request->get('admin_code'));
        $admin = $this->container->get('sonata.admin.pool')->getInstance($request->get('admin_code'));
        $admin->setRequest($request);
        $context = $request->get('_context', '');

        if ($context === 'filter' && false === $admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        if ($context !== 'filter'
            && false === $admin->isGranted('CREATE')
            && false === $admin->isGranted('EDIT')
        ) {
            throw new AccessDeniedException();
        }

        // subject will be empty to avoid unnecessary database requests and keep autocomplete function fast
        $admin->setSubject($admin->getNewInstance());

        if ($context === 'filter') {
            // filter
            $fieldDescription = $this->retrieveFilterFieldDescription($admin, $request->get('field'));
            $filterAutocomplete = $admin->getDatagrid()->getFilter($fieldDescription->getName());

            $property = $filterAutocomplete->getFieldOption('property');
            $callback = $filterAutocomplete->getFieldOption('callback');  // not used
            $minimumInputLength = $filterAutocomplete->getFieldOption('minimum_input_length', 3);
            $itemsPerPage = $filterAutocomplete->getFieldOption('items_per_page', 10);
            $reqParamPageNumber = $filterAutocomplete->getFieldOption('req_param_name_page_number', '_page'); // not used (TODO)
            $toStringCallback = $filterAutocomplete->getFieldOption('to_string_callback');
        } else {
            // create/edit form
            $fieldDescription = $this->retrieveFormFieldDescription($admin, $request->get('field'));
            $formAutocomplete = $admin->getForm()->get($fieldDescription->getName());

            if ($formAutocomplete->getConfig()->getAttribute('disabled')) {
                throw new AccessDeniedException('Autocomplete list can`t be retrieved because the form element is disabled or read_only.');
            }

            $property = $formAutocomplete->getConfig()->getAttribute('property');
            $callback = $formAutocomplete->getConfig()->getAttribute('callback');  // not used
            $minimumInputLength = $formAutocomplete->getConfig()->getAttribute('minimum_input_length');
            $itemsPerPage = $formAutocomplete->getConfig()->getAttribute('items_per_page');
            $reqParamPageNumber = $formAutocomplete->getConfig()->getAttribute('req_param_name_page_number');  // not used (TODO)
            $toStringCallback = $formAutocomplete->getConfig()->getAttribute('to_string_callback');
        }

        $searchText = $request->get('q');

        $targetAdmin = $fieldDescription->getAssociationAdmin();

        // check user permission
        if (false === $targetAdmin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        if (mb_strlen($searchText, 'UTF-8') < $minimumInputLength) {
            return new JsonResponse(array('status' => 'KO', 'message' => 'Too short search string.'), 403);
        }

        $class = $targetAdmin->getClass();
        $em = $this->getDoctrine()->getManager();
        $classMetadata = $em->getClassMetadata($class);
        $searchHandler = $this->getContainer()->get('blast_base_entities.search_handler');
        $searchHandler->handleEntity($classMetadata);
        $results = $searchHandler->indexSearch($searchText, $itemsPerPage);

        $items = [];
        foreach ($results as $entity) {
            if ($toStringCallback !== null) {
                if (!is_callable($toStringCallback)) {
                    throw new \RuntimeException('Option "to_string_callback" does not contain callable function.');
                }

                $label = call_user_func($toStringCallback, $entity, $property);
            } else {
                $resultMetadata = $targetAdmin->getObjectMetadata($entity);
                $label = $resultMetadata->getTitle();
            }

            $items[] = array(
                'id'    => $admin->id($entity),
                'label' => $label,
            );
        }

        return new JsonResponse(array(
            'status' => 'OK',
            'more'   => false,  // TODO !
            'items'  => $items,
        ));
    }

    /**
     * Retrieve the form field description given by field name.
     * Copied from Sonata\AdminBundle\Controller\HelperController.
     *
     * @param AdminInterface $admin
     * @param string         $field
     *
     * @return FormInterface
     *
     * @throws \RuntimeException
     */
    private function retrieveFormFieldDescription(AdminInterface $admin, $field)
    {
        $admin->getFormFieldDescriptions();

        $fieldDescription = $admin->getFormFieldDescription($field);

        if (!$fieldDescription) {
            throw new \RuntimeException(sprintf('The field "%s" does not exist.', $field));
        }

        if (null === $fieldDescription->getTargetEntity()) {
            throw new \RuntimeException(sprintf('No associated entity with field "%s".', $field));
        }

        return $fieldDescription;
    }
}
