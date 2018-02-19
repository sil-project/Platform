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

use Blast\Component\Resource\Model\ResourceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SimpleMessage extends AbstractMessage implements SimpleMessageInterface, ResourceInterface
{
    /**
     * From email address.
     *
     * @var EmailAddressInterface
     */
    protected $from;

    /**
     * To email address.
     *
     * @var Collection|EmailAddressInterface[]
     */
    protected $to;

    /**
     * CC email address.
     *
     * @var Collection|EmailAddressInterface[]
     */
    protected $cc;

    /**
     * BCC email address.
     *
     * @var Collection|EmailAddressInterface[]
     */
    protected $bcc;

    public function __construct(string $title, string $content, MessageTemplateInterface $template = null, EmailAddressInterface $from, EmailAddressInterface $to)
    {
        parent::__construct($title, $content, $template);

        $this->to = new ArrayCollection();
        $this->cc = new ArrayCollection();
        $this->bcc = new ArrayCollection();

        $this->from = $from;
        $this->addTo($to);
    }

    /**
     * {@inheritdoc}
     */
    public function getFrom(): EmailAddressInterface
    {
        return $this->from;
    }

    /**
     * {@inheritdoc}
     */
    public function getTo(): array
    {
        return $this->to->getValues();
    }

    /**
     * {@inheritdoc}
     */
    public function addTo(EmailAddressInterface $to): void
    {
        if ($this->to->contains($to)) {
            throw new InvalidArgumentException(sprintf('To « %s » is already set for message « %s »', $to, $this->getTitle()));
        }
        $this->to->add($to);
    }

    /**
     * {@inheritdoc}
     */
    public function removeTo(EmailAddressInterface $to): void
    {
        if (!$this->to->contains($to)) {
            throw new InvalidArgumentException(sprintf('To « %s » is not set for message « %s »', $to, $this->getTitle()));
        }
        if ($this->to->count() === 1) {
            throw new DomainException(
                sprintf(
                    'Message « %s » must have at least one recipient in To field.
                    Removing « %s » will result in an empty to field, wich is not a valid state for a message',
                    $this->getTitle(),
                    $to
                )
            );
        }
        $this->to->removeElement($to);
    }

    /**
     * {@inheritdoc}
     */
    public function getCc(): array
    {
        return $this->cc->getValues();
    }

    /**
     * {@inheritdoc}
     */
    public function addCc(EmailAddressInterface $cc): void
    {
        if ($this->cc->contains($cc)) {
            throw new InvalidArgumentException(sprintf('Cc address « %s » is already set for message « %s »', $cc, $this->getTitle()));
        }
        $this->cc->add($cc);
    }

    /**
     * {@inheritdoc}
     */
    public function removeCc(EmailAddressInterface $cc): void
    {
        if (!$this->cc->contains($cc)) {
            throw new InvalidArgumentException(sprintf('Cc address « %s » does not exists for message « %s »', $cc, $this->getTitle()));
        }
        $this->cc->removeElement($cc);
    }

    /**
     * {@inheritdoc}
     */
    public function getBcc(): array
    {
        return $this->bcc->getValues();
    }

    /**
     * {@inheritdoc}
     */
    public function addBcc(EmailAddressInterface $bcc): void
    {
        if ($this->bcc->contains($bcc)) {
            throw new InvalidArgumentException(sprintf('Bcc address « %s » is already set for message « %s »', $bcc, $this->getTitle()));
        }
        $this->bcc->add($bcc);
    }

    /**
     * {@inheritdoc}
     */
    public function removeBcc(EmailAddressInterface $bcc): void
    {
        if (!$this->bcc->contains($bcc)) {
            throw new InvalidArgumentException(sprintf('Bcc address « %s » does not exists for message « %s »', $bcc, $this->getTitle()));
        }
        $this->bcc->removeElement($bcc);
    }
}
