<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\ProfilerBundle\Collector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseCollector extends AbstractCollector
{
    /**=
     * @var mixed
     */
    private $hookRegistry;

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            DataCollection::DESTINATION_TOOLBAR  => [],
            DataCollection::DESTINATION_PROFILER => [],
        ];

        $collectedData = $this->collector->getData();

        foreach ($collectedData as $k => $dataCollection) {
            if ($this->isSerializable($dataCollection)) {
                $this->addToProfiler($k, $dataCollection->getName(), $dataCollection);
            }
        }
    }

    public function getName()
    {
        return 'blast.base_collector';
    }

    /**
     * @param mixed $hookRegistry
     */
    public function setHookRegistry($hookRegistry): void
    {
        $this->hookRegistry = $hookRegistry;
    }
}
