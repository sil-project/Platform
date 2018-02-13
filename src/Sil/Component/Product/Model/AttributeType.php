<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Product\Model;

use InvalidArgumentException;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Blast\Component\Resource\Model\ResourceInterface;

class AttributeType implements AttributeTypeInterface, ResourceInterface
{
    /**
     * Name of attribute.
     *
     * @var string
     */
    protected $name;

    /**
     * The data type of attribute type.
     *
     * @var int
     */
    protected $type;

    /**
     * The kind of attribute is aimed to be reusable ?
     *
     * @var bool
     */
    protected $reusable = false;

    /**
     * Collection of attributes (values) for this type of attribute.
     *
     * @var Collection|Attribute[]
     */
    protected $attributes;

    public function __construct($name, $type = null)
    {
        $this->attributes = new ArrayCollection();

        $this->name = $name;
        $this->type = $type ?? AttributeTypeInterface::TYPE_STRING;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array|Attribute[]
     */
    public function getAttributes(): array
    {
        return $this->attributes->getValues();
    }

    public function addAttribute(Attribute $attribute): void
    {
        if ($this->attributes->contains($attribute)) {
            throw new InvalidArgumentException(sprintf('Attribute « %s » for attribute type « %s » already exists', $attribute->getName(), $this->getName()));
        }
        $this->attributes->add($attribute);
    }

    public function removeAttribute(Attribute $attribute): void
    {
        if (!$this->attributes->contains($attribute)) {
            throw new InvalidArgumentException(sprintf('Attribute « %s » for attribute type « %s » does not exists', $attribute->getName(), $this->getName()));
        }
        $this->attributes->removeElement($attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedTypes(): array
    {
        return [
            'TYPE_BOOLEAN'  => AttributeTypeInterface::TYPE_BOOLEAN,
            'TYPE_STRING'   => AttributeTypeInterface::TYPE_STRING,
            'TYPE_INTEGER'  => AttributeTypeInterface::TYPE_INTEGER,
            'TYPE_FLOAT'    => AttributeTypeInterface::TYPE_FLOAT,
            'TYPE_PERCENT'  => AttributeTypeInterface::TYPE_PERCENT,
            'TYPE_DATE'     => AttributeTypeInterface::TYPE_DATE,
            'TYPE_DATETIME' => AttributeTypeInterface::TYPE_DATETIME,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(int $type): void
    {
        if (!in_array($type, $this->getSupportedTypes())) {
            throw new \InvalidArgumentException(sprintf('Unsupported attribute data type : "%s". Supported are : %s', $type, $this->getSupportedTypes()));
        }
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isReusable(): bool
    {
        return $this->reusable;
    }

    /**
     * @param bool $reusable
     */
    public function setReusable(bool $reusable = true): void
    {
        $this->reusable = $reusable;
    }
}
