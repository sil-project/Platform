<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductOptionValueAdmin extends SyliusGenericAdmin
{
    /**
     * @param FormMapper $mapper
     */
    public function configureFormFields(FormMapper $mapper)
    {
        parent::configureFormFields($mapper);

        // If form is embedded
        if ($this->getParentFieldDescription()) {
            $mapper->remove($this->getParentFieldDescription()->getAssociationMapping()['mappedBy']);
        }
    }
}
