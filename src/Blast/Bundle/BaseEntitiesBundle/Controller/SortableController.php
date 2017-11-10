<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Controller;

use Sonata\AdminBundle\Controller\CoreController;
use Sil\Bundle\DoctrineBundle\Entity\Repository\SortableRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\ORM\Mapping\ClassMetadata;

class SortableController extends CoreController
{
    /**
     * Move a sortable item.
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \RuntimeException
     * @throws AccessDeniedException
     */
    public function moveSortableItemAction(Request $request)
    {
        $admin = $this->container->get('sonata.admin.pool')->getInstance($request->get('admin_code'));
        $class = $admin->getClass();

        $id = $request->get('id');
        $prev_rank = (int) $request->get('prev_rank');
        $next_rank = (int) $request->get('next_rank');

        $em = $this->getDoctrine()->getEntityManager();
        $meta = new ClassMetadata($class);
        $repo = new SortableRepository($em, $meta);
        if ($prev_rank) {
            $new_rank = $repo->moveObjectAfter($id, $prev_rank);
        } elseif ($next_rank) {
            $new_rank = $repo->moveObjectBefore($id, $next_rank);
        }

        return new JsonResponse(array(
            'status'   => $new_rank ? 'OK' : 'KO',
            'new_rank' => $new_rank,
            'class'    => $admin->getClass(),
        ));
    }
}
