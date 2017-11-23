/*
 * Copyright (C) Paweł Jędrzejewski
 * Copyright (C) 2015-2016 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

(function ($) {
    'use strict';

    $.fn.api.settings.api = {
        'user check': '/sylius-shop/user-check',
        'login check': '/sylius-shop/login-check',
        'cart': '/sylius-shop/cart',
        'address book': '/sylius-shop/account/address-book?_format=json'
    };
})(jQuery);
