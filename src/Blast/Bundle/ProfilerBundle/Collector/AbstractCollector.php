<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\ProfilerBundle\Collector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;

abstract class AbstractCollector extends DataCollector
{
    const TYPE_NOT_MANAGED = 'Not Managed';

    /**
     * @var Collector
     */
    protected $collector;

    public function getData($name = null)
    {
        if ($name === null) {
            return $this->data;
        }

        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } else {
            return self::TYPE_NOT_MANAGED;
        }
    }

    /**
     * @return Collector
     */
    public function getCollector()
    {
        return $this->collector;
    }

    /**
     * @param Collector collector
     *
     * @return self
     */
    public function setCollector(Collector $collector)
    {
        $this->collector = $collector;

        return $this;
    }

    protected function getClassLink($class)
    {
        $reflector = new \ReflectionClass($class);

        return $reflector->getFileName();
    }

    protected function addToProfiler($rootKey, $key, $data)
    {
        if ($data instanceof DataCollection) {
            $this->data[$data->getDestination()][$rootKey][$key] = $data->getData();
        } else {
            switch ($data['display']) {
                case DataCollection::DESTINATION_TOOLBAR:
                case DataCollection::DESTINATION_PROFILER:
                    $this->data[$data['display']][$rootKey][$key] = $data;
                    break;
                case DataCollection::DESTINATION_BOTH:
                    $this->data[DataCollection::DESTINATION_TOOLBAR][$rootKey][$key] = $data;
                    $this->data[DataCollection::DESTINATION_PROFILER][$rootKey][$key] = $data;
                    break;
                default:
                    $this->data[DataCollection::DESTINATION_PROFILER][$rootKey][$key] = $data;
            }
        }
    }

    protected function isSerializable($value)
    {
        $serializable = true;
        $arr = [$value];

        array_walk_recursive($arr, function ($element) use (&$serializable) {
            if (is_object($element) && get_class($element) === 'Closure') {
                $serializable = false;
            }
        });

        return $serializable;
    }
}
