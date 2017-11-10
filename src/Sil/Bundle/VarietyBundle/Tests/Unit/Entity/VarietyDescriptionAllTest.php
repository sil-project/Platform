<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\VarietyBundle\Entity\Test\Unit;

use PHPUnit\Framework\TestCase;
use Sil\Bundle\VarietyBundle\Entity\VarietyDescriptionAmateur;
use Sil\Bundle\VarietyBundle\Entity\VarietyDescriptionCommercial;
use Sil\Bundle\VarietyBundle\Entity\VarietyDescriptionCulture;
use Sil\Bundle\VarietyBundle\Entity\VarietyDescriptionPlant;
use Sil\Bundle\VarietyBundle\Entity\VarietyDescriptionProfessional;

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
     * @covers \Sil\Bundle\VarietyBundle\Entity\VarietyDescriptionAmateur::getFieldset
     */
    public function testGetFieldsetAmateur()
    {
        $testAmat = $this->objectAmateur->getFieldset();
        $this->assertEquals($testAmat, 'amateur');
    }

    /**
     * @covers \Sil\Bundle\VarietyBundle\Entity\VarietyDescriptionCommercial::getFieldset
     */
    public function testGetFieldsetCommercial()
    {
        $testCom = $this->objectCommercial->getFieldset();
        $this->assertEquals($testCom, 'commercial');
    }

    /**
     * @covers \Sil\Bundle\VarietyBundle\Entity\VarietyDescriptionCulture::getFieldset
     */
    public function testGetFieldsetCulture()
    {
        $testCult = $this->objectCulture->getFieldset();
        $this->assertEquals($testCult, 'culture');
    }

    /**
     * @covers \Sil\Bundle\VarietyBundle\Entity\VarietyDescriptionPlant::getFieldset
     */
    public function testGetFieldsetPlant()
    {
        $testPlant = $this->objectPlant->getFieldset();
        $this->assertEquals($testPlant, 'plant');
    }

    /**
     * @covers \Sil\Bundle\VarietyBundle\Entity\VarietyDescriptionProfessional::getFieldset
     */
    public function testGetFieldsetProfessional()
    {
        $testPro = $this->objectProfessional->getFieldset();
        $this->assertEquals($testPro, 'professional');
    }
}
