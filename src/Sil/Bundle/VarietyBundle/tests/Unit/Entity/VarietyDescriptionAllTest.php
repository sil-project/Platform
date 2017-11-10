<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\VarietiesBundle\Entity\Test\Unit;

use PHPUnit\Framework\TestCase;
use Librinfo\VarietiesBundle\Entity\VarietyDescriptionAmateur;
use Librinfo\VarietiesBundle\Entity\VarietyDescriptionCommercial;
use Librinfo\VarietiesBundle\Entity\VarietyDescriptionCulture;
use Librinfo\VarietiesBundle\Entity\VarietyDescriptionPlant;
use Librinfo\VarietiesBundle\Entity\VarietyDescriptionProfessional;

class VarietyDescriptionAllTest extends TestCase
{
    /**
     * @var VarietyDescriptionAmateur
     */
    protected $objectAmateur;
    protected $objectCommercial;
    protected $objectCulture;
    protected $objectPlant;
    protected $objectProfessional;

    protected function setUp()
    {
        $this->objectAmateur = new VarietyDescriptionAmateur();
        $this->objectCommercial = new VarietyDescriptionCommercial();
        $this->objectCulture = new VarietyDescriptionCulture();
        $this->objectPlant = new VarietyDescriptionPlant();
        $this->objectProfessional = new VarietyDescriptionProfessional();
    }

    protected function tearDown()
    {
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\VarietyDescriptionAmateur::getFieldset
     */
    public function testGetFieldsetAmateur()
    {
        $testAmat = $this->objectAmateur->getFieldset();
        $this->assertEquals($testAmat, 'amateur');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\VarietyDescriptionCommercial::getFieldset
     */
    public function testGetFieldsetCommercial()
    {
        $testCom = $this->objectCommercial->getFieldset();
        $this->assertEquals($testCom, 'commercial');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\VarietyDescriptionCulture::getFieldset
     */
    public function testGetFieldsetCulture()
    {
        $testCult = $this->objectCulture->getFieldset();
        $this->assertEquals($testCult, 'culture');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\VarietyDescriptionPlant::getFieldset
     */
    public function testGetFieldsetPlant()
    {
        $testPlant = $this->objectPlant->getFieldset();
        $this->assertEquals($testPlant, 'plant');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\VarietyDescriptionProfessional::getFieldset
     */
    public function testGetFieldsetProfessional()
    {
        $testPro = $this->objectProfessional->getFieldset();
        $this->assertEquals($testPro, 'professional');
    }
}
