<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SonataSyliusUserBundle\Entity;

use Blast\Component\Resource\Model\ResourceInterface;

/**
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 * @author Marcos Bezerra de Meneze <marcos.bezerra@libre-informatique.fr>
 */
interface SonataGroupInterface extends ResourceInterface
{
    /**
     * @param string $role
     *
     * @return self
     */
    public function addRole($role);

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $role
     *
     * @return bool
     */
    public function hasRole($role);

    /**
     * @return array
     */
    public function getRoles();

    /**
     * @param string $role
     *
     * @return self
     */
    public function removeRole($role);

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName($name);

    /**
     * @param array $roles
     *
     * @return self
     */
    public function setRoles(array $roles);
}
