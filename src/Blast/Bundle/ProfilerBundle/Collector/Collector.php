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

class Collector
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * @var array
     */
    private $collectedKeys;

    public function __construct()
    {
        $this->data = [];
        $this->collectedKeys = [];
    }

    /**
     * @param string $name
     * @param mixed  $data
     * @param string $destination
     *
     * @return $this
     */
    public function collect($name, $data, $destination = DataCollection::DESTINATION_PROFILER)
    {
        $this->collectedKeys[] = $name;

        $dataCollection = new DataCollection($name, $data, $destination);
        $this->data[$this->handleDataKey($name)] = $dataCollection;

        return $this;
    }

    /**
     * @param string $name
     * @param mixed  $data
     * @param string $destination
     *
     * @return $this
     */
    public function collectOnce($name, $data, $destination = DataCollection::DESTINATION_PROFILER)
    {
        if (!in_array($name, $this->collectedKeys)) {
            $this->collectedKeys[] = $name;
            $dataCollection = new DataCollection($name, $data, $destination);
            $this->data[$this->handleDataKey($name)] = $dataCollection;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    private function handleDataKey($name)
    {
        $keyStrucure = '#%d %s';

        return sprintf($keyStrucure, count($this->data) + 1, $name);
    }
}
