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

use DomainException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class MailingList implements MailingListInterface
{
    /**
     * the name of mailing list.
     *
     * @var string
     */
    protected $name;

    /**
     * Description of mailing list.
     *
     * @var string
     */
    protected $description;

    /**
     * The list is enabled.
     *
     * @var bool
     */
    protected $enabled = false;

    /**
     * Collection of list's recipients.
     *
     * @var Collection|RecipientInterface[]
     */
    protected $recipients;

    public function __construct(string $name, ?string $description = null)
    {
        $this->name = $name;
        $this->description = $description;

        $this->recipients = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled(bool $enabled): void
    {
        if ($this->recipients->count() === 0) {
            throw new DomainException(sprintf('MailingList « %s » cannot be enabled because it has no recipients', $this->getName()));
        }
        $this->enabled = $enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function getRecipients(): array
    {
        return $this->recipients->getValues();
    }

    /**
     * {@inheritdoc}
     */
    public function addRecipient(RecipientInterface $recipient): void
    {
        if ($this->recipients->contains($recipient)) {
            throw new InvalidArgumentException(sprintf('Recipient « %s » already belong to list « %s »', $recipient->getEmail(), $this->getName()));
        }
        $this->recipients->add($recipient);
    }

    /**
     * {@inheritdoc}
     */
    public function removeRecipient(RecipientInterface $recipient): void
    {
        if (!$this->recipients->contains($recipient)) {
            throw new InvalidArgumentException(sprintf('Recipient « %s » does not belongs to list « %s »', $recipient->getEmail(), $this->getName()));
        }
        $this->recipients->removeElement($recipient);
    }
}
