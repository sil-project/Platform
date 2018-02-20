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
use Blast\Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class GroupedMessage extends AbstractMessage implements GroupedMessageInterface, ResourceInterface
{
    /**
     * Mailing lists for the grouped message.
     *
     * @var Collection|MailingListInterface
     */
    protected $lists;

    public function __construct(string $title, string $content)
    {
        parent::__construct($title, $content);

        $this->lists = new ArrayCollection();
    }

    /**
     * @return array|MailingListInterface[]
     */
    public function getLists(): array
    {
        return $this->lists->getValues();
    }

    /**
     * @param MailingListInterface $list
     *
     * @throws InvalidArgumentException
     */
    public function addList(MailingListInterface $list): void
    {
        if ($this->lists->contains($list)) {
            throw new InvalidArgumentException(sprintf('List « %s » is already used by message « %s »', $list->getName(), $this->getTitle()));
        }
        $this->lists->add($list);
    }

    /**
     * @param MailingListInterface $list
     *
     * @throws InvalidArgumentException
     */
    public function removeList(MailingListInterface $list): void
    {
        if (!$this->lists->contains($list)) {
            throw new InvalidArgumentException(sprintf('List « %s » is not used by message « %s »', $list->getName(), $this->getTitle()));
        }
        $this->lists->removeElement($list);
    }
}
