<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SeedBatchBundle\Entity;

use Blast\Bundle\BaseEntitiesBundle\Entity\SearchIndexEntity;

class PlotSearchIndex extends SearchIndexEntity
{
    // TODO: this should go in the contact.orm.yml mapping file :
    //       find a way to override Doctrine ORM YamlDriver and ClassMetadata classes

    public static $fields = ['name', 'code', 'address', 'zip', 'city', 'country']; // TODO...
}
