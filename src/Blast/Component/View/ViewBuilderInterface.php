<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\View;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
interface ViewBuilderInterface extends \Traversable, \Countable
{
    public function add($child, $type = null, array $options = array());

    /**
     * Creates a view builder.
     *
     * @param string      $name    The name of the view or the name of the property
     * @param string|null $type    The type of the view or null if name is a property
     * @param array       $options The options
     *
     * @return self
     */
    public function create($name, $type = null, array $options = array());

    /**
     * Returns a child by name.
     *
     * @param string $name The name of the child
     *
     * @return self
     *
     * @throws Exception\InvalidArgumentException if the given child does not exist
     */
    public function get($name);

    /**
     * Removes the field with the given name.
     *
     * @param string $name
     *
     * @return self
     */
    public function remove($name);

    /**
     * Returns whether a field with the given name exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name);

    /**
     * Returns the children.
     *
     * @return array
     */
    public function all();

    /**
     * Creates the view.
     *
     * @return ViewInterface The view
     */
    public function getView();
}
