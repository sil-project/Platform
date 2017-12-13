<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Uom\Tests\Unit\Fixture;

use Sil\Component\Uom\Repository\UomRepositoryInterface;
use Sil\Component\Uom\Repository\UomTypeRepositoryInterface;
use Sil\Component\Uom\Tests\Unit\InMemoryRepository\UomRepository;
use Sil\Component\Uom\Tests\Unit\InMemoryRepository\UomTypeRepository;
use Sil\Component\Uom\Model\Uom;
use Sil\Component\Uom\Model\UomType;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
trait UomFixturesTrait
{
    /**
     * @var UomRepositoryInterface
     */
    protected $uomRepository;

    /**
     * @var UomTypeRepositoryInterface
     */
    protected $uomTypeRepository;

    public function loadMassUomFixtures()
    {
        $this->initializeRepositories();

        $uomTypeMass = $this->createUomType('Mass');
        $this->createUom($uomTypeMass, 'T', 0.001);
        $this->createUom($uomTypeMass, 'Kg', 1);
        $this->createUom($uomTypeMass, 'g', 1000);
        $this->createUom($uomTypeMass, 'mg', 1000000);
    }

    public function loadLengthUomFixtures()
    {
        $this->initializeRepositories();

        $uomTypeLength = $this->createUomType('Length');
        $this->createUom($uomTypeLength, 'Km', 0.001);
        $this->createUom($uomTypeLength, 'm', 1);
        $this->createUom($uomTypeLength, 'cm', 100);
        $this->createUom($uomTypeLength, 'mm', 1000);
    }

    public function getUomByName(string $name)
    {
        return $this->uomRepository->findOneBy(['name'=>$name]);
    }

    public function createUom(UomType $type, string $name, float $factor)
    {
        $uom = $this->getUomClass()::createDefault($type, $name, $factor);
        $this->uomRepository->add($uom);

        return $uom;
    }

    public function createUomType($name)
    {
        $uomType = $this->getUomTypeClass()::createDefault($name);
        $this->uomTypeRepository->add($uomType);

        return $uomType;
    }

    protected function initializeRepositories()
    {
        $this->uomRepository = ($this->uomRepository ?: new UomRepository($this->getUomClass()));
        $this->uomTypeRepository = ($this->uomTypeRepository ?: new UomTypeRepository($this->getUomTypeClass()));
    }

    public function getUomClass()
    {
        return Uom::class;
    }

    public function getUomTypeClass()
    {
        return UomType::class;
    }
}
