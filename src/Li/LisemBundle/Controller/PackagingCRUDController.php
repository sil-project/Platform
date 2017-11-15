<?php

/*
 * This file is part of the Lisem Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Controller;

use Blast\Bundle\CoreBundle\Controller\CRUDController as BaseController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class PackagingCRUDController extends BaseController
{
    protected function preCreate(Request $request, $object)
    {
    }
}
