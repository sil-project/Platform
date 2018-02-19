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

class MessageTemplate implements MessageTemplateInterface, ResourceInterface
{
    /**
     * The template name.
     *
     * @var string
     */
    protected $name;

    /**
     * The template base content.
     *
     * @var string
     */
    protected $content;

    /**
     * Collection of token types used by the template.
     *
     * @var Collection|ContentTokenTypeInterface[]
     */
    protected $tokenTypes;

    /**
     * Collection of message that uses this template.
     *
     * @var Collection|MessageInterface[]
     */
    protected $messages;

    public function __construct(string $name, string $content)
    {
        $this->name = $name;
        $this->content = $content;

        $this->tokenTypes = new ArrayCollection();
        $this->messages = new ArrayCollection();
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
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenTypes(): array
    {
        return $this->tokenTypes->getValues();
    }

    /**
     * {@inheritdoc}
     */
    public function addTokenType(ContentTokenTypeInterface $token): void
    {
        if ($this->tokenTypes->contains($token)) {
            throw new InvalidArgumentException(sprintf('TokenType « %s » is already associated to template « %s »', $token->getName(), $this->getName()));
        }
        $this->tokenTypes->add($token);
    }

    /**
     * {@inheritdoc}
     */
    public function removeTokenType(ContentTokenTypeInterface $token): void
    {
        if (!$this->tokenTypes->contains($token)) {
            throw new InvalidArgumentException(sprintf('TokenType « %s » is not associated to template « %s »', $token->getName(), $this->getName()));
        }
        $this->tokenTypes->removeElement($token);
    }

    /**
     * @return array|MessageInterface[]
     */
    public function getMessages(): array
    {
        return $this->messages->getValues();
    }

    /**
     * @param MessageInterface $message
     *
     * @throws InvalidArgumentException
     */
    public function addMessage(MessageInterface $message): void
    {
        if ($this->messages->contains($message)) {
            throw new InvalidArgumentException(sprintf('Message « %s » is already using template « %s »', $message->getTitle(), $this->getName()));
        }
        $this->messages->add($message);
    }

    /**
     * @param MessageInterface $message
     *
     * @throws InvalidArgumentException
     */
    public function removeMessage(MessageInterface $message): void
    {
        if (!$this->messages->contains($message)) {
            throw new InvalidArgumentException(sprintf('Message « %s » is not using template « %s »', $message->getTitle(), $this->getName()));
        }
        $this->messages->removeElement($message);
    }
}
