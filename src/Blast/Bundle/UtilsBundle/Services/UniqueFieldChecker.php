<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UtilsBundle\Services;

use Doctrine\ORM\EntityManager;
use Sonata\AdminBundle\Admin\Pool;

/**
 * Check if an object with the same value for the same field exists.
 */
class UniqueFieldChecker
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager, Pool $adminPool, \Twig_Environment $twig)
    {
        $this->manager = $manager;
        $this->adminPool = $adminPool;
        $this->twig = $twig;
    }

    /**
     * @param string $className
     * @param string $fieldName
     * @param string $value
     *
     * @return array
     */
    public function check($className, $fieldName, $value)
    {
        $repo = $this->manager->getRepository($className);

        $result = [
            'available' => true,
            'object'    => $repo->findOneBy([$fieldName => $value]),
        ];

        if ($result['object'] != null) {
            $result['available'] = false;
        }

        return $result;
    }

    /**
     * Render html error.
     *
     * @param array $result
     *
     * @return string result as html
     */
    public function renderResult($object, $value, $adminCode = null)
    {
        $link = '';

        if ($object && $adminCode) {
            $admin = $this->adminPool->getAdminByAdminCode($adminCode);

            $link = $admin->generateObjectUrl('edit', $object);
        }

        return $this->twig->render('BlastUtilsBundle:Form:unique_field_result.html.twig', [
            'link'        => $link,
            'value'       => $value,
            'unavailable' => $object !== null,
        ]);
    }
}
