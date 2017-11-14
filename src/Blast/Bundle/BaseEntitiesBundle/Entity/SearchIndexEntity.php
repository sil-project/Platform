<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Entity;

abstract class SearchIndexEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $keyword;

    /**
     * @var string
     */
    protected $field;

    /**
     * @var object Entity
     */
    protected $object;

    /**
     * @param string $field
     *
     * @return self
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $keyword
     *
     * @return self
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * @param object $object
     *
     * @return self
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }
}
