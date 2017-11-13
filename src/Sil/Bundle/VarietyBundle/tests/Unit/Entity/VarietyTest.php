<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\VarietiesBundle\Entity\Test\Unit;

use PHPUnit\Framework\TestCase;
use Librinfo\VarietiesBundle\Entity\Variety;
use Doctrine\Common\Collections\ArrayCollection;
use Librinfo\VarietiesBundle\Entity\VarietyDescriptionPlant;
use AppBundle\Entity\OuterExtension\LibrinfoVarietiesBundle\VarietyExtension;

class VarietyTest extends TestCase
{
    /**
     * @var Variety
     */
    protected $object;
    protected $mockVarietyDescriptionProfessional;
    protected $mockVariety;
    protected $mockPlantCategory;
    protected $mockSpecies;
    protected $mockTraitNameable;
    protected $mockTraitOuterExtensible;
    protected $mockGenericDescription;
    protected $mockVarietyDescriptionAmateur;
    protected $mockProductionDescription;
    protected $mockCommercialDescription;
    protected $mockInnerDescription;
    protected $mockVarietyDescriptionCulture;
    protected $mockVarietyDescriptionPlant;
    protected $varietydescriptionplant;
    protected $getvalue;

    use VarietyExtension;

    protected function setUp()
    {
        $this->object = new Variety();
        $this->mockTraitNameable = $this->getMockForTrait('\Blast\BaseEntitiesBundle\Entity\Traits\Nameable');
        $this->mockTraitOuterExtensible = $this->getMockForTrait('\Blast\OuterExtensionBundle\Entity\Traits\OuterExtensible');
        $this->mockVarietyDescriptionCulture = $this->createMock('\Librinfo\VarietiesBundle\Entity\VarietyDescriptionCulture');
        $this->mockSpecies = $this->createMock('\Librinfo\VarietiesBundle\Entity\Species');
        $this->mockVariety = $this->createMock('\Librinfo\VarietiesBundle\Entity\Variety');
        $this->mockVarietyDescriptionAmateur = $this->createMock('\Librinfo\VarietiesBundle\Entity\VarietyDescriptionAmateur');
        $this->mockVarietyDescriptionProfessional = $this->createMock('\Librinfo\VarietiesBundle\Entity\VarietyDescriptionProfessional');
        $this->mockPlantCategory = $this->createMock('\Librinfo\VarietiesBundle\Entity\PlantCategory');
        $this->mockVarietyDescriptionPlant = $this->createMock('\Librinfo\VarietiesBundle\Entity\VarietyDescriptionPlant');
        $this->mockProductionDescription = $this->createMock('\Librinfo\VarietiesBundle\Entity\VarietyDescriptionProduction');
        $this->mockCommercialDescription = $this->createMock('\Librinfo\VarietiesBundle\Entity\VarietyDescriptionCommercial');
        $this->mockInnerDescription = $this->createMock('\Librinfo\VarietiesBundle\Entity\VarietyDescriptionInner');

        $this->varietydescriptionplant = new VarietyDescriptionPlant();
        $this->varietydescriptionplant->setField('foliage_type');
        $this->varietydescriptionplant->setValue('testvalue');
        $this->getvalue = $this->varietydescriptionplant->getValue();
        $this->object->AddPlantDescription($this->varietydescriptionplant);
    }

    protected function tearDown()
    {
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::initCollections
     */
    public function testInitCollections()
    {
        $this->object->InitCollections();
        $this->assertInstanceOf(ArrayCollection::class, $this->object->getChildren());
        $this->assertInstanceOf(ArrayCollection::class, $this->object->getPlantCategories());
        $this->assertInstanceOf(ArrayCollection::class, $this->object->getProfessionalDescriptions());
        $this->assertInstanceOf(ArrayCollection::class, $this->object->getAmateurDescriptions());
        $this->assertInstanceOf(ArrayCollection::class, $this->object->getProductionDescriptions());
        $this->assertInstanceOf(ArrayCollection::class, $this->object->getCommercialDescriptions());
        $this->assertInstanceOf(ArrayCollection::class, $this->object->getPlantDescriptions());
        $this->assertInstanceOf(ArrayCollection::class, $this->object->getCultureDescriptions());
        $this->assertInstanceOf(ArrayCollection::class, $this->object->getImages());
        $this->assertInstanceOf(ArrayCollection::class, $this->object->getInnerDescriptions());
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::__clone
     *
     *  @todo test method __clone call this method initOuterExtendedClasses();
     */
    public function test__clone()
    {
        $this->object->setId('testid');
        $this->object->setCode('testcode');
        //testing clone method
        $clone = clone $this->object;
        $this->assertNull($clone->getId());
        $this->assertNull($clone->getCode());
        $this->assertInstanceOf(ArrayCollection::class, $clone->getChildren());
        //testing the original object after clonage
        $this->assertEquals('testid', $this->object->getId());
        $this->assertEquals('testcode', $this->object->getCode());
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::__call
     *
     * @todo  I have to put the method name in variable because of the operator || used in  method name (maybe need to change it in the __call method of Variety.php)
     */
    public function test__call()
    {
        $test = 'plant||foliage_type';
        $testcall = $this->object->$test();
        $this->assertEquals($testcall, $this->getvalue);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getName
     */
    public function testGetName()
    {
        //testing return $this->getNameTrait();
        $get = $this->object->getName();
        $this->assertEquals($get, '');

        //testing return $this->getParent()->getName();
        $this->mockTraitNameable->setName(null);
        $this->assertEquals($this->object->getName(), $this->mockTraitNameable->getName());
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::hasParent
     */
    public function testHasParent()
    {
        $has = $this->object->hasParent();
        $this->assertNotNull($has);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getSeveralStrains
     */
    public function testGetSeveralStrains()
    {
        $get = $this->object->getSeveralStrains();
        $this->assertGreaterThan($get, 1);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setLatinName
     */
    public function testSetLatinName()
    {
        $test = 'test';
        $this->object->setLatinName($test);
        $get = $this->object->getLatinName();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getLatinName
     */
    public function testGetLatinName()
    {
        $get = $this->object->getLatinName();
        $this->assertEquals($get, '');
        //testing return $this->getParent()->getLatinName();
        $this->object->setLatinName(false);
        $get2 = $this->object->getLatinName();
        $getlatin = $this->mockVariety->getLatinName();
        $this->assertEquals($get2, $getlatin);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setIsStrain
     */
    public function testSetIsStrain()
    {
        $test = 'test';
        $this->object->setIsStrain($test);
        $get = $this->object->getIsStrain();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getIsStrain
     */
    public function testGetIsStrain()
    {
        $get = $this->object->getIsStrain();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setAlias
     */
    public function testSetAlias()
    {
        $test = 'test';
        $this->object->setAlias($test);
        $get = $this->object->getAlias();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getAlias
     */
    public function testGetAlias()
    {
        $get = $this->object->getAlias();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setCode
     */
    public function testSetCode()
    {
        $test = 'test';
        $this->object->setCode($test);
        $get = $this->object->getCode();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getCode
     */
    public function testGetCode()
    {
        $get = $this->object->getCode();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setLifeCycle
     */
    public function testSetLifeCycle()
    {
        $test = 'test';
        $this->object->setLifeCycle($test);
        $get = $this->object->getLifeCycle();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getLifeCycle
     */
    public function testGetLifeCycle()
    {
        $get = $this->object->getLifeCycle();
        $this->assertEquals($get, '');
        //testing return $this->getParent()->getLifeCycle();
        $this->object->setLifeCycle(false);
        $get2 = $this->object->getLifeCycle();
        $getlife = $this->mockVariety->getLifeCycle();
        $this->assertEquals($get2, $getlife);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setOfficial
     */
    public function testSetOfficial()
    {
        $test = 'test';
        $this->object->setOfficial($test);
        $get = $this->object->getOfficial();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getOfficial
     */
    public function testGetOfficial()
    {
        $get = $this->object->getOfficial();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setOfficialName
     */
    public function testSetOfficialName()
    {
        $test = 'test';
        $this->object->setOfficialName($test);
        $get = $this->object->getOfficialName();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getOfficialName
     */
    public function testGetOfficialName()
    {
        $get = $this->object->getOfficialName();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setOfficialDateIn
     */
    public function testSetOfficialDateIn()
    {
        $date = '2017-24-05';
        $this->object->setOfficialDateIn($date);
        $get = $this->object->getOfficialDateIn();
        $this->assertEquals($get, '2017-24-05');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getOfficialDateIn
     */
    public function testGetOfficialDateIn()
    {
        $get = $this->object->getOfficialDateIn();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setOfficialDateOut
     */
    public function testSetOfficialDateOut()
    {
        $date = 'test';
        $this->object->setOfficialDateOut($date);
        $get = $this->object->getOfficialDateOut();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getOfficialDateOut
     */
    public function testGetOfficialDateOut()
    {
        $get = $this->object->getOfficialDateOut();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setOfficialMaintainer
     */
    public function testSetOfficialMaintainer()
    {
        $test = 'test';
        $this->object->setOfficialMaintainer($test);
        $get = $this->object->getOfficialMaintainer();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getOfficialMaintainer
     */
    public function testGetOfficialMaintainer()
    {
        $get = $this->object->getOfficialMaintainer();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setLegalGerminationRate
     */
    public function testSetLegalGerminationRate()
    {
        $test = 'test';
        $this->object->setLegalGerminationRate($test);
        $get = $this->object->getLegalGerminationRate();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getLegalGerminationRate
     *
     * @todo add test to this return
     *          if (!$this->legal_germination_rate && $this->getSpecies())
     *              return $this->getSpecies()->getLegalGerminationRate();
     */
    public function testGetLegalGerminationRate()
    {
        $get = $this->object->getLegalGerminationRate();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setRegulatoryStatus
     */
    public function testSetRegulatoryStatus()
    {
        $test = 'test';
        $this->object->setRegulatoryStatus($test);
        $get = $this->object->getRegulatoryStatus();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getRegulatoryStatus
     */
    public function testGetRegulatoryStatus()
    {
        $get = $this->object->getRegulatoryStatus();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setGerminationRate
     */
    public function testSetGerminationRate()
    {
        $test = 'test';
        $this->object->setGerminationRate($test);
        $get = $this->object->getGerminationRate();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getGerminationRate
     */
    public function testGetGerminationRate()
    {
        $get = $this->object->getGerminationRate();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setParent
     */
    public function testSetParent()
    {
        $this->object->setParent($this->mockVariety = null);
        $get = $this->object->getParent();
        $this->assertNull($get);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getParent
     */
    public function testGetParent()
    {
        $get = $this->object->getParent();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::addChild
     */
    public function testAddChild()
    {
        $this->object->addChild($this->mockVariety);
        $array = $this->object->getChildren();
        $this->assertContains($this->mockVariety, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::removeChild
     */
    public function testRemoveChild()
    {
        $this->object->addChild($this->mockVariety);
        $array = $this->object->getChildren();
        $this->object->removeChild($this->mockVariety);
        $this->assertNotcontains($this->mockVariety, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getChildren
     */
    public function testGetChildren()
    {
        $get = $this->object->getChildren();
        $this->assertInstanceOf(ArrayCollection::class, $get);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setSpecies
     */
    public function testSetSpecies()
    {
        $this->object->setSpecies($this->mockSpecies = null);
        $get = $this->object->getSpecies();
        $this->assertNull($get);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getSpecies
     */
    public function testGetSpecies()
    {
        $get = $this->object->getSpecies();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::addPlantCategory
     */
    public function testAddPlantCategory()
    {
        $this->object->addPlantCategory($this->mockPlantCategory);
        $test = $this->object->getPlantCategories();
        $this->assertContains($this->mockPlantCategory, $test);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::removePlantCategory
     */
    public function testRemovePlantCategory()
    {
        $this->object->addPlantCategory($this->mockPlantCategory);
        $array = $this->object->getPlantCategories();
        $this->object->removePlantCategory($this->mockPlantCategory);
        $this->assertNotcontains($this->mockPlantCategory, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getPlantCategories
     *
     * @todo  test not complete need to test this  return $this->getSpecies()->getPlantCategories();
     */
    public function testGetPlantCategories()
    {
        $get = $this->object->getPlantCategories();
        $this->assertInstanceOf(ArrayCollection::class, $get);
        $this->assertNotNull($get);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setPlantCategories
     */
    public function testSetPlantCategories()
    {
        $plant_categories = $this->object->getPlantCategories();
        $this->object->setPlantCategories($plant_categories);
        $this->assertEquals($plant_categories, $this->object->getPlantCategories());
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setSelectionAdvice
     */
    public function testSetSelectionAdvice()
    {
        $test = 'test';
        $this->object->setSelectionAdvice($test);
        $get = $this->object->getSelectionAdvice();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getSelectionAdvice
     */
    public function testGetSelectionAdvice()
    {
        $get = $this->object->getSelectionAdvice();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setSelectionCriteria
     */
    public function testSetSelectionCriteria()
    {
        $test = 'test';
        $this->object->setSelectionCriteria($test);
        $get = $this->object->getSelectionCriteria();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getSelectionCriteria
     */
    public function testGetSelectionCriteria()
    {
        $get = $this->object->getSelectionCriteria();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setMiscAdvice
     */
    public function testSetMiscAdvice()
    {
        $test = 'test';
        $this->object->setMiscAdvice($test);
        $get = $this->object->getMiscAdvice();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getMiscAdvice
     */
    public function testGetMiscAdvice()
    {
        $get = $this->object->getMiscAdvice();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setTkw
     */
    public function testSetTkw()
    {
        $test = 'test';
        $this->object->setTkw($test);
        $get = $this->object->getTkw();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getTkw
     *
     * @todo   Implement testGetTkw().
     */
    public function testGetTkw()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setSeedLifespan
     */
    public function testSetSeedLifespan()
    {
        $test = 'test';
        $this->object->setSeedLifespan($test);
        $get = $this->object->getSeedLifespan();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getSeedLifespan
     *
     * @todo    add Test to this return $this->getSpecies()->getSeedLifeSpan();
     */
    public function testGetSeedLifespan()
    {
        $get = $this->object->getSeedLifespan();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setRaiseDuration
     *
     * @todo   Implement testSetRaiseDuration().
     */
    public function testSetRaiseDuration()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getRaiseDuration
     *
     * @todo   Implement testGetRaiseDuration().
     */
    public function testGetRaiseDuration()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setSeedheadYield
     */
    public function testSetSeedheadYield()
    {
        $test = 'test';
        $this->object->setSeedheadYield($test);
        $get = $this->object->getSeedheadYield();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getSeedheadYield
     */
    public function testGetSeedheadYield()
    {
        $get = $this->object->getSeedheadYield();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setDistanceOnLine
     */
    public function testSetDistanceOnLine()
    {
        $test = 'test';
        $this->object->setDistanceOnLine($test);
        $get = $this->object->getDistanceOnLine();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getDistanceOnLine
     */
    public function testGetDistanceOnLine()
    {
        $get = $this->object->getDistanceOnLine();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setDistanceBetweenLines
     */
    public function testSetDistanceBetweenLines()
    {
        $test = 'test';
        $this->object->setDistanceBetweenLines($test);
        $get = $this->object->getDistanceBetweenLines();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getDistanceBetweenLines
     */
    public function testGetDistanceBetweenLines()
    {
        $get = $this->object->getDistanceBetweenLines();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setPlantDensity
     */
    public function testSetPlantDensity()
    {
        $test = 'test';
        $this->object->setPlantDensity($test);
        $get = $this->object->getPlantDensity();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getPlantDensity
     */
    public function testGetPlantDensity()
    {
        $get = $this->object->getPlantDensity();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setAreaPerKg
     */
    public function testSetAreaPerKg()
    {
        $test = 'test';
        $this->object->setAreaPerKg($test);
        $get = $this->object->getAreaPerKg();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getAreaPerKg
     */
    public function testGetAreaPerKg()
    {
        $get = $this->object->getAreaPerKg();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setSeedheadsPerKg
     */
    public function testSetSeedheadsPerKg()
    {
        $test = 'test';
        $this->object->setSeedheadsPerKg($test);
        $get = $this->object->getSeedheadsPerKg();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getSeedheadsPerKg
     */
    public function testGetSeedheadsPerKg()
    {
        $get = $this->object->getSeedheadsPerKg();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setBaseSeedPerKg
     */
    public function testSetBaseSeedPerKg()
    {
        $test = 'test';
        $this->object->setBaseSeedPerKg($test);
        $get = $this->object->getBaseSeedPerKg();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getBaseSeedPerKg
     */
    public function testGetBaseSeedPerKg()
    {
        $get = $this->object->getBaseSeedPerKg();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setTransmittedDiseases
     */
    public function testSetTransmittedDiseases()
    {
        $test = 'test';
        $this->object->setTransmittedDiseases($test);
        $get = $this->object->getTransmittedDiseases();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getTransmittedDiseases
     */
    public function testGetTransmittedDiseases()
    {
        $get = $this->object->getTransmittedDiseases();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setStrainCharacteristics
     */
    public function testSetStrainCharacteristics()
    {
        $test = 'test';
        $this->object->setStrainCharacteristics($test);
        $get = $this->object->getStrainCharacteristics();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getStrainCharacteristics
     */
    public function testGetStrainCharacteristics()
    {
        $get = $this->object->getStrainCharacteristics();
        $this->assertEquals($get, '');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::addProfessionalDescription
     */
    public function testAddProfessionalDescription()
    {
        $this->object->addProfessionalDescription($this->mockVarietyDescriptionProfessional);
        $array = $this->object->getProfessionalDescriptions();
        $this->assertContains($this->mockVarietyDescriptionProfessional, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::removeProfessionalDescription
     */
    public function testRemoveProfessionalDescription()
    {
        $this->object->addProfessionalDescription($this->mockVarietyDescriptionProfessional);
        $array = $this->object->getProfessionalDescriptions();
        $this->object->removeProfessionalDescription($this->mockVarietyDescriptionProfessional);
        $this->assertNotcontains($this->mockVarietyDescriptionProfessional, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getProfessionalDescriptions
     */
    public function testGetProfessionalDescriptions()
    {
        $get = $this->object->getProfessionalDescriptions();
        $this->assertInstanceOf(ArrayCollection::class, $get);
        $this->assertNotNull($get);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getProfessional_descriptions
     */
    public function testGetProfessional_descriptions()
    {
        $get = $this->object->getProfessional_descriptions();
        $get2 = $this->object->getProfessionalDescriptions();
        $this->assertEquals($get, $get2);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setProfessionalDescriptions
     *
     * @todo   Implement testSetProfessionalDescriptions().
     */
    public function testSetProfessionalDescriptions()
    {
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::addAmateurDescription
     */
    public function testAddAmateurDescription()
    {
        $this->object->addAmateurDescription($this->mockVarietyDescriptionAmateur);
        $array = $this->object->getAmateurDescriptions();
        $this->assertContains($this->mockVarietyDescriptionAmateur, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::removeAmateurDescription
     */
    public function testRemoveAmateurDescription()
    {
        $this->object->addAmateurDescription($this->mockVarietyDescriptionAmateur);
        $array = $this->object->getAmateurDescriptions();
        $this->object->removeAmateurDescription($this->mockVarietyDescriptionAmateur);
        $this->assertNotcontains($this->mockVarietyDescriptionAmateur, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getAmateurDescriptions
     */
    public function testGetAmateurDescriptions()
    {
        $get = $this->object->getAmateurDescriptions();
        $this->assertInstanceOf(ArrayCollection::class, $get);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getAmateur_descriptions
     */
    public function testGetAmateur_descriptions()
    {
        $get = $this->object->getAmateur_descriptions();
        $get2 = $this->object->getAmateurDescriptions();
        $this->assertEquals($get, $get2);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setAmateurDescriptions
     *
     * @todo   Implement testSetAmateurDescriptions().
     */
    public function testSetAmateurDescriptions()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::addProductionDescription
     */
    public function testAddProductionDescription()
    {
        $this->object->addProductionDescription($this->mockProductionDescription);
        $array = $this->object->getProductionDescriptions();
        $this->assertContains($this->mockProductionDescription, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::removeProductionDescription
     */
    public function testRemoveProductionDescription()
    {
        $this->object->addProductionDescription($this->mockProductionDescription);
        $array = $this->object->getProductionDescriptions();
        $this->object->removeProductionDescription($this->mockProductionDescription);
        $this->assertNotcontains($this->mockProductionDescription, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getProductionDescriptions
     */
    public function testGetProductionDescriptions()
    {
        $get = $this->object->getProductionDescriptions();
        $this->assertInstanceOf(ArrayCollection::class, $get);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getProduction_descriptions
     */
    public function testGetProduction_descriptions()
    {
        $get = $this->object->getProduction_descriptions();
        $get2 = $this->object->getProductionDescriptions();
        $this->assertEquals($get, $get2);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setProductionDescriptions
     *
     * @todo   Implement testSetProductionDescriptions().
     */
    public function testSetProductionDescriptions()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::addCommercialDescription
     */
    public function testAddCommercialDescription()
    {
        $this->object->addCommercialDescription($this->mockCommercialDescription);
        $array = $this->object->getCommercialDescriptions();
        $this->assertContains($this->mockCommercialDescription, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::removeCommercialDescription
     */
    public function testRemoveCommercialDescription()
    {
        $this->object->addCommercialDescription($this->mockCommercialDescription);
        $array = $this->object->getCommercialDescriptions();
        $this->object->removeCommercialDescription($this->mockCommercialDescription);
        $this->assertNotcontains($this->mockCommercialDescription, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getCommercialDescriptions
     */
    public function testGetCommercialDescriptions()
    {
        $get = $this->object->getCommercialDescriptions();
        $this->assertInstanceOf(ArrayCollection::class, $get);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getCommercial_descriptions
     */
    public function testGetCommercial_descriptions()
    {
        $get = $this->object->getCommercial_descriptions();
        $get2 = $this->object->getCommercialDescriptions();
        $this->assertEquals($get, $get2);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setCommercialDescriptions
     *
     * @todo   Implement testSetCommercialDescriptions().
     */
    public function testSetCommercialDescriptions()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::addPlantDescription
     */
    public function testAddPlantDescription()
    {
        $this->object->addPlantDescription($this->mockVarietyDescriptionPlant);
        $array = $this->object->getPlantDescriptions();
        $this->assertContains($this->mockVarietyDescriptionPlant, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::removePlantDescription
     */
    public function testRemovePlantDescription()
    {
        $this->object->addPlantDescription($this->mockVarietyDescriptionPlant);
        $array = $this->object->getCommercialDescriptions();
        $this->object->removePlantDescription($this->mockVarietyDescriptionPlant);
        $this->assertNotcontains($this->mockVarietyDescriptionPlant, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getPlantDescriptions
     */
    public function testGetPlantDescriptions()
    {
        $get = $this->object->getPlantDescriptions();
        $this->assertInstanceOf(ArrayCollection::class, $get);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getPlant_descriptions
     */
    public function testGetPlant_descriptions()
    {
        $get = $this->object->getPlant_descriptions();
        $get2 = $this->object->getPlantDescriptions();
        $this->assertEquals($get, $get2);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setPlantDescriptions
     *
     * @todo   Implement testSetPlantDescriptions().
     */
    public function testSetPlantDescriptions()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::addCultureDescription
     */
    public function testAddCultureDescription()
    {
        $this->object->addCultureDescription($this->mockVarietyDescriptionCulture);
        $array = $this->object->getCultureDescriptions();
        $this->assertContains($this->mockVarietyDescriptionCulture, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::removeCultureDescription
     */
    public function testRemoveCultureDescription()
    {
        $this->object->addCultureDescription($this->mockVarietyDescriptionCulture);
        $array = $this->object->getCommercialDescriptions();
        $this->object->removeCultureDescription($this->mockVarietyDescriptionCulture);
        $this->assertNotcontains($this->mockVarietyDescriptionCulture, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getCultureDescriptions
     */
    public function testGetCultureDescriptions()
    {
        $get = $this->object->getCultureDescriptions();
        $this->assertInstanceOf(ArrayCollection::class, $get);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getCulture_descriptions
     */
    public function testGetCulture_descriptions()
    {
        $get = $this->object->getCulture_descriptions();
        $get2 = $this->object->getCultureDescriptions();
        $this->assertEquals($get, $get2);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setCultureDescriptions
     *
     * @todo   Implement testSetCultureDescriptions().
     */
    public function testSetCultureDescriptions()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::addInnerDescription
     */
    public function testAddInnerDescription()
    {
        $this->object->addInnerDescription($this->mockInnerDescription);
        $array = $this->object->getInnerDescriptions();
        $this->assertContains($this->mockInnerDescription, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::removeInnerDescription
     */
    public function testRemoveInnerDescription()
    {
        $this->object->addInnerDescription($this->mockInnerDescription);
        $array = $this->object->getInnerDescriptions();
        $this->object->removeInnerDescription($this->mockInnerDescription);
        $this->assertNotcontains($this->mockInnerDescription, $array);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getInnerDescriptions
     */
    public function testGetInnerDescriptions()
    {
        $get = $this->object->getInnerDescriptions();
        $this->assertInstanceOf(ArrayCollection::class, $get);
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setInnerDescriptions
     */
    public function testSetInnerDescriptions()
    {
        $test = 'test';
        $this->object->setInnerDescriptions($test);
        $get = $this->object->getInnerDescriptions();
        $this->assertEquals($get, 'test');
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::addLibrinfoFile
     *
     * @todo   Implement testAddLibrinfoFile().
     */
    public function testAddLibrinfoFile()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::removeLibrinfoFile
     *
     * @todo   Implement testRemoveLibrinfoFile().
     */
    public function testRemoveLibrinfoFile()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setName
     *
     * @todo   Implement testSetName().
     */
    public function testSetName()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getNameTrait
     *
     * @todo   Implement testGetNameTrait().
     */
    public function testGetNameTrait()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getId
     *
     * @todo   Implement testGetId().
     */
    public function testGetId()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setId
     *
     * @todo   Implement testSetId().
     */
    public function testSetId()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::isNew
     *
     * @todo   Implement testIsNew().
     */
    public function testIsNew()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::__toString
     *
     * @todo   Implement test__toString().
     */
    public function test__toString()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::addImage
     *
     * @todo   Implement testAddImage().
     */
    public function testAddImage()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::removeImage
     *
     * @todo   Implement testRemoveImage().
     */
    public function testRemoveImage()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getImages
     *
     * @todo   Implement testGetImages().
     */
    public function testGetImages()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setImages
     *
     * @todo   Implement testSetImages().
     */
    public function testSetImages()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getLibrinfoFiles
     *
     * @todo   Implement testGetLibrinfoFiles().
     */
    public function testGetLibrinfoFiles()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getCreatedAt
     *
     * @todo   Implement testGetCreatedAt().
     */
    public function testGetCreatedAt()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setCreatedAt
     *
     * @todo   Implement testSetCreatedAt().
     */
    public function testSetCreatedAt()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getUpdatedAt
     *
     * @todo   Implement testGetUpdatedAt().
     */
    public function testGetUpdatedAt()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setUpdatedAt
     *
     * @todo   Implement testSetUpdatedAt().
     */
    public function testSetUpdatedAt()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::setDescription
     *
     * @todo   Implement testSetDescription().
     */
    public function testSetDescription()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers \Librinfo\VarietiesBundle\Entity\Variety::getDescription
     *
     * @todo   Implement testGetDescription().
     */
    public function testGetDescription()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
}
