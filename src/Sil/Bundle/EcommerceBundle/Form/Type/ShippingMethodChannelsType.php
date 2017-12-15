<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Form\Type;

class ShippingMethodChannelsType extends AnyChannelsType
{
    public function getBlockPrefix()
    {
        return 'sil_type_shipping_method_channels';
    }
}
