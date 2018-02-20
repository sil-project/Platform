<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @group variety
 * @group seedbatch
 * @group all
 */
use Blast\Bundle\TestsBundle\Codeception\Step\Acceptance\Lisem as LisemTester;
use Blast\Bundle\TestsBundle\Codeception\Step\Acceptance\Variety as VarietyTester;
use Blast\Bundle\TestsBundle\Codeception\Step\Acceptance\SeedBatch as SeedBatchTester;

class CreateVarietyAndSeedBatchCest
{
    /* Variety Var */
    protected $selFamily;
    protected $selGenus;
    protected $selPlantCat;
    protected $selSpecies;
    protected $selVariety;

    /* SeedBatch Var */
    protected $selSeedBatch;
    protected $selProducer;
    protected $selPlot;

    /* Ah Ah always Needed */
    public function testLogin(LisemTester $lisem)
    {
        //$lisem->hideSymfonyToolBar();
        $lisem->loginLisem();
    }

    /**
     * @depends testLogin
     */
    public function testFamily(VarietyTester $variety)
    {
        $variety->loginLisem();
        $this->selFamily = $variety->createFamily();
    }

    /**
     * @depends testLogin
     * @depends testFamily
     */
    public function testGenus(VarietyTester $variety)
    {
        $variety->loginLisem();
        $this->selGenus = $variety->createGenus($this->selFamily);
    }

    /**
     * @depends testLogin
     */
    public function testPlantCategory(VarietyTester $variety)
    {
        $variety->loginLisem();
        $this->selPlantCat = $variety->createPlantCategory();
    }

    /**
     * @depends testLogin
     * @depends testGenus
     * @depends testPlantCategory
     */
    public function testSpecies(VarietyTester $variety)
    {
        $variety->loginLisem();
        $this->selSpecies = $variety->createSpecies($this->selGenus, $this->selPlantCat);
    }

    /**
     * @depends testLogin
     * @depends testSpecies
     * @depends testPlantCategory
     */
    public function testVariety(VarietyTester $variety)
    {
        $variety->loginLisem();
        $this->selVariety = $variety->createVariety($this->selSpecies, $this->selPlantCat);
    }

    /**
     * @depends testLogin
     */
    public function testProducter(SeedBatchTester $seedbatch)
    {
        $seedbatch->loginLisem();
        $this->selProducer = $seedbatch->createProducer();
        //warning we replace producer name because search box for it does not work well with sel-1234
        $this->selProducer = $seedbatch->getRandNbr();
    }

    /**
     * @depends testLogin
     * @depends testProducter
     */
    public function testPlot(SeedBatchTester $seedbatch)
    {
        $seedbatch->loginLisem();
        $this->selPlot = $seedbatch->createPlot($this->selProducer);
    }

    /**
     * @depends testLogin
     * @depends testProducter
     * @depends testPlot
     * @depends testVariety
     */
    public function testSeedBatch(SeedBatchTester $seedbatch)
    {
        $seedbatch->loginLisem();
        $this->selSeedBatch = $seedbatch->createSeedBatch($this->selVariety, $this->selProducer, $this->selPlot);
    }
}
