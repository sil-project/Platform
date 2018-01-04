<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\Resource\Metadata;

/**
 * Description of Metadata.
 *
 * @author glenn
 */
class ClassMap implements ClassMapInterface
{
    private $model;
    private $repository;
    private $interfaces;

    private function __construct()
    {
    }

    public static function fromArray(array $parameters)
    {
        $classMap = new self();
        $classMap->model = $parameters['model'] ?? null;
        $classMap->repository = $parameters['repository'] ?? null;
        $classMap->interfaces = $parameters['interfaces'] ?? [];

        self::checkDuplicates($classMap);
        self::checkInterfaces($classMap);

        return $classMap;
    }

    public function hasModel()
    {
        return null !== $this->model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function hasRepository()
    {
        return null !== $this->repository;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function hasInterfaces()
    {
        return 0 < count($this->interfaces);
    }

    public function getInterfaces()
    {
        return $this->interfaces;
    }

    private static function checkDuplicates(ClassMapInterface $classMap)
    {
        $interfaces = $classMap->interfaces;
        if (count(array_unique($interfaces)) < count($interfaces)) {
            $duplicates = array_keys(array_filter(array_count_values($interfaces), function ($v, $k) {
                return $v > 1;
            }, ARRAY_FILTER_USE_BOTH));

            throw new \InvalidArgumentException(
              sprintf("Duplicate declaration of interfaces for model %s : \n - %s", $classMap->model, implode("\n- ", $duplicates)));
        }
    }

    private static function checkInterfaces(ClassMapInterface $classMap)
    {
        $implementedInterfaces = class_implements($classMap->model);
        foreach ($classMap->interfaces as $interface) {
            if (!in_array($interface, $implementedInterfaces)) {
                throw new \InvalidArgumentException(sprintf('Invalid interface "%s" supplied, this interface have to be implemented by %s.', $interface, $classMap->model));
            }
        }
    }
}
