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

use Blast\Component\Code\Repository\CodeAwareRepositoryInterface;
use Blast\Component\Code\Tests\Unit\Repository\TestCodeRepository;

class Fixtures
{
    /**
     * @var CodeAwareRepositoryInterface
     */
    private $codeRepository;

    private $rawData = [];

    public function __construct()
    {
        $this->rawData = [
            'code' => [
                'prefix' => 'CDE',
            ],
        ];

        $this->codeRepository = new TestCodeRepository(TestCode::class);

        $this->generateFixtures();
    }

    private function generateFixtures()
    {
        $this->generateCodes();
    }

    private function generateCodes()
    {
        foreach ([
            '2020-03-27',
            '2020-03-28',
            '2020-03-29',
            '2020-03-30',
            '2020-03-31',
        ] as $date) {
            $this->getCodeRepository()->add(
                new TestCode($this->rawData['code']['prefix'] . '-' . str_replace('-', '', $date))
            );
        }
    }

    /**
     * @return CodeAwareRepositoryInterface
     */
    public function getCodeRepository(): CodeAwareRepositoryInterface
    {
        return $this->codeRepository;
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return $this->rawData;
    }
}
