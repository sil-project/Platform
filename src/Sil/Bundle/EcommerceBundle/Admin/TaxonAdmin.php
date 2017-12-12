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

class TaxonAdmin extends SyliusGenericAdmin
{
    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.ecommerce.taxon';

    protected $baseRouteName = 'admin_ecommerce_taxon';
    protected $baseRoutePattern = 'ecommerce/taxon';

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query->orderBy('o.root', 'ASC');
        $query->orderBy('o.left', 'ASC');

        return $query;
    }

    public function prePersist($object)
    {
        parent::prePersist($object);

        $slugGenerator = $this->getConfigurationPool()->getContainer()->get('sylius.generator.slug');

        $object->setSlug($slugGenerator->generate($object->getName()));
    }
}
