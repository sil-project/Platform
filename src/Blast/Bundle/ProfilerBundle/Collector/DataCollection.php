<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\ProfilerBundle\Collector;

class DataCollection
{
    const DESTINATION_TOOLBAR = 'toolbar';
    const DESTINATION_PROFILER = 'profiler';
    const DESTINATION_BOTH = 'both';

    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var string
     */
    private $destination;

    public function __construct($name, $data, $destination = self::DESTINATION_PROFILER)
    {
        $this->name = $name;
        $this->data = $data;
        $this->destination = $destination;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed data
     *
     * @return self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param string destination
     *
     * @return self
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }
}
