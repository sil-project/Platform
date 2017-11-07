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

namespace Librinfo\EcommerceBundle\Admin;

use Blast\CoreBundle\Admin\Traits\EmbeddedAdmin;

class ChannelPricingAdmin extends SyliusGenericAdmin
{
    use EmbeddedAdmin;

    protected $baseRouteName = 'admin_librinfo_ecommerce_channelpricing';
    protected $baseRoutePattern = 'librinfo/ecommerce/channelpricing';

    protected function configureRoutes(\Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        parent::configureRoutes($collection);
        $collection->remove('create');
    }

    /**
     * @return ProductVariantInterface
     */
    public function getNewInstance()
    {
        $object = parent::getNewInstance();
        if ($this->getProductVariant()) {
            $object->setProductVariant($this->getProductVariant());
        }

        return $object;
    }

    public function getProductVariant()
    {
        if ($this->productVariant) {
            return $this->productVariant;
        }

        if ($this->subject && $productVariant = $this->subject->getProduct()) {
            $this->productVariant = $productVariant;

            return $productVariant;
        }

        if ($productVariant_id = $this->getRequest()->get('productVariant_id')) {
            $productVariant = $this->getConfigurationPool()->getContainer()->get('sylius.repository.product_variant')->find($productVariant_id);
            if (!$productVariant) {
                throw new \Exception(sprintf('Unable to find Product Variant with id : %s', $productVariant_id));
            }
            $this->productVariant = $productVariant;

            return $productVariant;
        }

        return null;
    }
}
