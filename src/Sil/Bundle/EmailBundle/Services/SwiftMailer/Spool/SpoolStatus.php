<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EmailBundle\Services\SwiftMailer\Spool;

interface SpoolStatus
{
    const STATUS_FAILED = -1;
    const STATUS_READY = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_COMPLETE = 3;
}
