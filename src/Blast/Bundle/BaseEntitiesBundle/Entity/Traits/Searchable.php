<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Entity\Traits;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\Common\Collections\ArrayCollection;
use Blast\Bundle\BaseEntitiesBundle\Search\SearchAnalyser;

trait Searchable
{
    /**
     * @var ArrayCollection
     */
    protected $searchIndexes;

    public function __construct()
    {
        $this->searchIndexes = new ArrayCollection();
    }

    public function addSearchIndex($searchIndex)
    {
        $this->searchIndexes->add($searchIndex);

        return $this;
    }

    public function removeSearchIndex($searchIndex)
    {
        $this->searchIndexes->removeElement($searchIndex);
    }

    public function getSearchIndexes()
    {
        return $this->searchIndexes;
    }

    public function setSearchIndexes(ArrayCollection $searchIndexes)
    {
        $this->searchIndexes = $searchIndexes;

        return $this;
    }

    /**
     * @param string $field
     *
     * @return array
     *
     * @throws \Exception
     */
    public function analyseField($field)
    {
        try {
            $accessor = PropertyAccess::createPropertyAccessor();

            $data = $accessor->getValue($this, $field);
        } catch (\Exception $exc) {
            throw new \Exception("Property $field does not exist for " . get_class());
        }

        return SearchAnalyser::analyse($data);
    }
}
