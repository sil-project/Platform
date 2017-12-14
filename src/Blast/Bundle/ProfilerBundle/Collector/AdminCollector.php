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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sonata\AdminBundle\Mapper\BaseMapper;
use Sonata\AdminBundle\Mapper\BaseGroupedMapper;

class AdminCollector extends AbstractCollector
{
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            DataCollection::DESTINATION_TOOLBAR  => [],
            DataCollection::DESTINATION_PROFILER => [],
        ];

        $collectedData = $this->collector->getData();


        $this->collector->collect('# Hooks', $data, DataCollection::DESTINATION_PROFILER);

        dump($this->collector);

        $hooks = 0;

        foreach ($collectedData as $k => $dataCollection) {
            $data = $dataCollection->getData();

            if (preg_replace('/\#[0-9]*\W/', '', $k) === 'Managed classes') {
                $this->addToProfiler($k, 'Managed classes', [
                    'display'         => DataCollection::DESTINATION_TOOLBAR, // 'toolbar', 'profiler', 'both'
                    'class'           => count($data),
                ]);
            }

            if (preg_replace('/^\#[0-9]*\W/', '', $k) === 'Hook') {
                ++$hooks;
            }

            if ($data instanceof BaseGroupedMapper || $data instanceof BaseMapper) {
                $entity = $data->getAdmin()->getClass();
                $admin = $data->getAdmin();

                $this->addToProfiler($k, 'entity', [
                    'display' => DataCollection::DESTINATION_PROFILER,
                    'class'   => $entity,
                    'file'    => $this->getClassLink($entity),
                ]);

                $this->addToProfiler($k, 'admin', [
                    'display' => DataCollection::DESTINATION_PROFILER,
                    'class'   => get_class($admin),
                    'file'    => $this->getClassLink(get_class($admin)),
                ]);

                $this->addToProfiler($k, 'form tabs / groups', [
                    'display' => DataCollection::DESTINATION_PROFILER,
                    'class'   => count($admin->getFormTabs()) . ' / ' . count($admin->getFormGroups()),
                ]);

                $this->addToProfiler($k, 'form', [
                    'display'         => DataCollection::DESTINATION_PROFILER,
                    'Tabs and Groups' => [
                        'tabs'   => $admin->getFormTabs(),
                        'groups' => $admin->getFormGroups(),
                    ],
                ]);

                $this->addToProfiler($k, 'show tabs / groups', [
                    'display' => DataCollection::DESTINATION_PROFILER,
                    'class'   => count($admin->getShowTabs()) . ' / ' . count($admin->getShowGroups()),
                ]);

                $this->addToProfiler($k, 'show', [
                    'display'         => DataCollection::DESTINATION_PROFILER,
                    'Tabs and Groups' => [
                        'tabs'   => $admin->getShowTabs(),
                        'groups' => $admin->getShowGroups(),
                    ],
                ]);
            } else {
                $this->addToProfiler($k, $dataCollection->getName(), $dataCollection);
            }

            $this->addToProfiler('Hook registered', 'Hooks', [
                'display'         => DataCollection::DESTINATION_TOOLBAR, // 'toolbar', 'profiler', 'both'
                'class'           => $hooks,
            ]);
        }
    }

    public function getName()
    {
        return 'blast.admin_collector';
    }
}
