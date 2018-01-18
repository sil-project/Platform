<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PlatformBundle\Profiler;

use Blast\Bundle\ProfilerBundle\Collector\AbstractCollector;
use Blast\Bundle\ProfilerBundle\Collector\DataCollection;
use Sonata\AdminBundle\Mapper\BaseGroupedMapper;
use Sonata\AdminBundle\Mapper\BaseMapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlastCollector extends AbstractCollector
{
    /**
     * @var mixed
     */
    private $hookRegistry;

    /**
     * @var array
     */
    private $blastParameter;

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            DataCollection::DESTINATION_TOOLBAR  => [],
            DataCollection::DESTINATION_PROFILER => [],
        ];

        $collectedData = $this->collector->getData();

        $this->collectBlastAdminData($collectedData);

        if ($this->hookRegistry !== null) {
            $this->collectBlastHookData();
        }
    }

    private function collectBlastAdminData($collectedData)
    {
        foreach ($collectedData as $k => $dataCollection) {
            $k = preg_replace('/\#[0-9]*\W/', '', $k);
            $data = $dataCollection->getData();

            if ($k === 'Managed classes') {
                $parameters = [];
                foreach ($data as $class) {
                    if (array_key_exists($class, $this->blastParameter)) {
                        $parameters[$class] = $this->blastParameter[$class];
                    }
                }

                $this->addToProfiler('Blast parameters', 'parameters', [
                    'display'    => DataCollection::DESTINATION_PROFILER,
                    'parameters' => $parameters,
                ]);

                $this->addToProfiler($k, 'Managed classes', [
                    'display' => DataCollection::DESTINATION_TOOLBAR,
                    'class'   => count($data),
                ]);
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
        }
    }

    private function collectBlastHookData()
    {
        $hooks = $this->hookRegistry->getRegistredHooks();
        $i = 1;
        foreach ($hooks as $hookName => $hook) {
            $hookTitle = 'Hook #' . $i;

            $this->addToProfiler($hookTitle, 'name', [
                'display' => DataCollection::DESTINATION_PROFILER,
                'name'    => $hookName,
            ]);
            $this->addToProfiler($hookTitle, 'class', [
                'display' => DataCollection::DESTINATION_PROFILER,
                'class'   => get_class($hook[0]),
            ]);
            $i++;
        }

        $this->addToProfiler('Hooks', 'Hooks', [
            'display' => DataCollection::DESTINATION_TOOLBAR,
            'class'   => count($hooks),
        ]);
    }

    public function getName()
    {
        return 'blast.blast_collector';
    }

    /**
     * @param mixed $hookRegistry
     */
    public function setHookRegistry($hookRegistry): void
    {
        $this->hookRegistry = $hookRegistry;
    }

    /**
     * @param array $blastParameter
     */
    public function setBlastParameter(array $blastParameter): void
    {
        $this->blastParameter = $blastParameter;
    }
}
