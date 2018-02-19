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
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

abstract class AbstractMessage implements MessageInterface, MessageStateAwareInterface
{
    use MessageStateAwareTrait;

    /**
     * Title of the message.
     *
     * @var string
     */
    protected $title;

    /**
     * Content of the message.
     *
     * @var string
     */
    protected $content;

    /**
     * State of current message.
     *
     * @var MessageStateInterface
     */
    protected $state;

    /**
     * Collection of attachments.
     *
     * @var Collection|AttachmentInterface[]
     */
    protected $attachments;

    /**
     * The message template.
     *
     * @var MessageTemplateInterface
     */
    protected $template;

    /**
     * Collection of substitution tokens.
     *
     * @var Collection|ContentTokenInterface[]
     */
    protected $tokens;

    public function __construct(string $title, string $content, MessageTemplateInterface $template = null)
    {
        $this->title = $title;
        $this->content = $content;
        $this->template = $template;

        $this->state = MessageState::draft();

        $this->attachments = new ArrayCollection();
        $this->tokens = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttachments(): array
    {
        return $this->attachments->getValues();
    }

    /**
     * {@inheritdoc}
     */
    public function addAttachment(AttachmentInterface $attachment): void
    {
        if ($this->attachments->contains($attachment)) {
            throw new InvalidArgumentException(sprintf('Attachment « %s » is already attached to message « %s »', $attachment->getName(), $this->getTitle()));
        }
        $this->attachments->add($attachment);
    }

    /**
     * {@inheritdoc}
     */
    public function removeAttachment(AttachmentInterface $attachment): void
    {
        if (!$this->attachments->contains($attachment)) {
            throw new InvalidArgumentException(sprintf('Attachment « %s » is not attached to message « %s »', $attachment->getName(), $this->getTitle()));
        }
        $this->attachments->removeElement($attachment);
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate(): ?MessageTemplateInterface
    {
        return $this->template;
    }

    /**
     * {@inheritdoc}
     */
    public function setTemplate(?MessageTemplateInterface $template): void
    {
        $this->template = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function getTokens(): array
    {
        return $this->tokens->getValues();
    }

    /**
     * {@inheritdoc}
     */
    public function addToken(ContentTokenInterface $token): void
    {
        if ($this->tokens->contains($token)) {
            throw new InvalidArgumentException(sprintf('Token « %s : %s » is already assigned to the message « %s »', $token->getName(), $token->getValue(), $this->getTitle()));
        }
        $this->tokens->add($token);
    }

    /**
     * {@inheritdoc}
     */
    public function removeToken(ContentTokenInterface $token): void
    {
        if (!$this->tokens->contains($token)) {
            throw new InvalidArgumentException(sprintf('Token « %s : %s » is not assigned to the message « %s »', $token->getName(), $token->getValue(), $this->getTitle()));
        }
        $this->tokens->removeElement($token);
    }
}
