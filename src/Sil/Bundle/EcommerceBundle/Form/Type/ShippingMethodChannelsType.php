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

namespace Sil\Bundle\EcommerceBundle\Form\Type;

class ShippingMethodChannelsType extends AnyChannelsType
{
    public function getBlockPrefix()
    {
        return 'librinfo_type_shipping_method_channels';
    }
}
