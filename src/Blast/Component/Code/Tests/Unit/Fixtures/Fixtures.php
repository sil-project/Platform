<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Component\Code\Tests\Unit\Fixtures;

use DateTime;
use Blast\Component\Code\Repository\CodeAwareRepositoryInterface;
use Blast\Component\Code\Tests\Unit\Repository\TestEntityRepository;

class Fixtures
{
    /**
     * @var CodeAwareRepositoryInterface
     */
    private $testEntityRepository;

    private $rawData = [];

    public function __construct()
    {
        $this->rawData = [
            'code' => [
                'prefix' => 'CDE',
            ],
        ];

        $this->testEntityRepository = new TestEntityRepository(TestEntity::class);

        $this->generateFixtures();
    }

    private function generateFixtures()
    {
        $this->generateCodes();
    }

    private function generateCodes()
    {
        $codeGenerator = new TestCodeGenerator();
        foreach ([
            '2020-03-27',
            '2020-03-28',
            '2020-03-29',
            '2020-03-30',
            '2020-03-31',
        ] as $date) {
            $code =
            $this->getTestEntityRepository()->add(
                new TestEntity($codeGenerator->generate($this->rawData['code']['prefix'], new DateTime($date)))
            );
        }
    }

    /**
     * @return CodeAwareRepositoryInterface
     */
    public function getTestEntityRepository(): CodeAwareRepositoryInterface
    {
        return $this->testEntityRepository;
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return $this->rawData;
    }
}
