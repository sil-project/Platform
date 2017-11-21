<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Admin;

use Blast\Bundle\CoreBundle\Admin\CoreAdmin;

/**
 * Sonata admin for ChannelDescriptions.
 *
 * @author Romain SANCHEZ <romain.sanchez@libre-informatique.fr>
 */
class ChannelDescriptionAdmin extends CoreAdmin
{
    protected $baseRouteName = 'admin_lisem_channel_description';
    protected $baseRoutePattern = 'lisem/channel_description';
}
