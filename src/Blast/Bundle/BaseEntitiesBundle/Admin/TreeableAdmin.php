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

namespace Blast\Bundle\BaseEntitiesBundle\Admin;

use Blast\Bundle\CoreBundle\Admin\CoreAdmin;

abstract class TreeableAdmin extends CoreAdmin
{
    /**
     * {@inheritdoc}
     */
    public function create($object)
    {
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();

        $this->prePersist($object);
        foreach ($this->extensions as $extension) {
            $extension->prePersist($this, $object);
        }

        $object->setMaterializedPath('');
        $em->persist($object);

        if ($object->getParentNode() !== null) {
            $object->setChildNodeOf($object->getParentNode());
        } else {
            $object->setParentNode(null);
        }

        $this->postPersist($object);
        foreach ($this->extensions as $extension) {
            $extension->postPersist($this, $object);
        }

        $em->flush();
        $this->createObjectSecurity($object);

        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function getObject($id)
    {
        $object = parent::getObject($id);

        $parent_node_id = $object->getParentNodeId();
        $parent_node = $this->getModelManager()->find($this->getClass(), $parent_node_id);
        $object->setParentNode($parent_node);

        return $object;
    }
}
