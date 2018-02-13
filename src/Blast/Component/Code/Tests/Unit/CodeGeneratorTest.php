<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\Code\Tests\Unit;

use DateTime;
use PHPUnit\Framework\TestCase;
use Blast\Component\Code\Tests\Unit\Fixtures\Fixtures;
use Blast\Component\Code\Tests\Unit\Fixtures\TestCodeGenerator;

class CodeGeneratorTest extends TestCase
{
    /**
     * @var array
     */
    private $fixtures;

    public function setUp()
    {
        $this->fixtures = new Fixtures();
    }

    public function testExampleCodeGeneration()
    {
        $generator = new TestCodeGenerator();

        $date = new DateTime('2020-04-01');

        $code = $generator->generate($this->fixtures->getRawData()['code']['prefix'], $date);

        $this->assertEquals($code->getValue(), $this->fixtures->getRawData()['code']['prefix'] . '-20200401');
    }

    public function testValidCodeValidation()
    {
        $generator = new TestCodeGenerator();

        $date = new DateTime('2020-04-01');

        $code = $generator->generate($this->fixtures->getRawData()['code']['prefix'], $date);

        $this->assertTrue($generator->isValid($code));
    }

    public function testSuccessValidatingWithRepositoryForNewCode()
    {
        $generator = new TestCodeGenerator();
        $generator->setCodeAwareRepository($this->fixtures->getCodeRepository());

        $code = $generator->generate($this->fixtures->getRawData()['code']['prefix'], new DateTime('2020-04-01'));

        $this->assertTrue($generator->isValid($code));
        $this->assertTrue($generator->isUnique($code));
    }

    public function testFailValidatingWithRepositoryForNewCode()
    {
        $generator = new TestCodeGenerator();
        $generator->setCodeAwareRepository($this->fixtures->getCodeRepository());

        $this->expectException(\DomainException::class);

        $code = $generator->generate($this->fixtures->getRawData()['code']['prefix'], new DateTime('2020-03-31'));
    }
}
