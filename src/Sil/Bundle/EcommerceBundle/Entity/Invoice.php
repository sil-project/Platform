<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Entity;

use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Timestampable;

/**
 * Invoice Entity.
 *
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class Invoice
{
    use
        BaseEntity,
        Timestampable
    ;

    const TYPE_DEBIT = 0;
    const TYPE_CREDIT = 1;

    /**
     * @var string
     */
    private $number;

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var int
     */
    private $size;

    /**
     * @var string
     */
    private $file;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var int
     */
    private $total;

    /**
     * @var int
     */
    private $type = self::TYPE_DEBIT;

    /**
     * Called by __toString().
     *
     * @return string
     */
    public function getName()
    {
        return $this->number;
    }

    /**
     * Set number.
     *
     * @param string $number
     *
     * @return Invoice
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set mimeType.
     *
     * @param string $mimeType
     *
     * @return Invoice
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType.
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set size.
     *
     * @param int $size
     *
     * @return Invoice
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size.
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set file.
     *
     * @param string $file
     *
     * @return Invoice
     */
    public function setFile($file)
    {
        $this->file = base64_encode($file);

        return $this;
    }

    /**
     * Get file.
     *
     * @return string
     */
    public function getFile()
    {
        return base64_decode($this->file);
    }

    /**
     * Set total.
     *
     * @param int $total
     *
     * @return Invoice
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total.
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set order.
     *
     * @param Order $order
     *
     * @return Invoice
     */
    public function setOrder(\Sil\Bundle\EcommerceBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order.
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function __toString()
    {
        return (string) $this->number;
    }
}
