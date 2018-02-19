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

class ContentTokenType implements ContentTokenTypeInterface, ResourceInterface
{
    /**
     * Name of the token type.
     *
     * @var string
     */
    protected $name;

    /**
     * The data type that the token value will use.
     *
     * @var ContentTokenDataType
     */
    protected $dataType;

    /**
     * The temple wich token type belongs.
     *
     * @var MessageTemplateInterface
     */
    protected $template;

    public function __construct(MessageTemplateInterface $template, string $name, ContentTokenDataType $dataType)
    {
        $this->name = $name;
        $this->dataType = $dataType;
        $this->template = $template;
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
    public function getDataType(): ContentTokenDataType
    {
        return $this->dataType;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate(): MessageTemplateInterface
    {
        return $this->template;
    }
}
