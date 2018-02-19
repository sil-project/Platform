<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Emailing\Model;

use InvalidArgumentException;

interface GroupedMessageInterface
{
    /**
     * @return array|MailingListInterface[]
     */
    public function getLists(): array;

    /**
     * @param MailingListInterface $list
     *
     * @throws InvalidArgumentException
     */
    public function addList(MailingListInterface $list): void;

    /**
     * @param MailingListInterface $list
     *
     * @throws InvalidArgumentException
     */
    public function removeList(MailingListInterface $list): void;
}
