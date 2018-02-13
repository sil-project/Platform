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
use DomainException;
use Blast\Component\Code\Generator\AbstractCodeGenerator;
use Blast\Component\Code\Model\CodeInterface;

class TestCodeGenerator extends AbstractCodeGenerator
{
    public function generate(string $prefix, DateTime $date): CodeInterface
    {
        $code = new TestCode(sprintf('%s-%s', $prefix, $date->format('Ymd')));

        if (!$this->isValid($code)) {
            throw new DomainException(sprintf('The generated code %s does not fit the regex format %s', $code, $code->getFormat()));
        }

        if (!$this->isUnique($code)) {
            throw new DomainException(sprintf('The generated code for prefix %s and date %s is not unique', $prefix, $date->format('Ymd')));
        }

        return $code;
    }
}
