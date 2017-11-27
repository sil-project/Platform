<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\VarietyBundle\Admin;

use Blast\Bundle\CoreBundle\Admin\CoreAdmin;
use Blast\Bundle\CoreBundle\Admin\Traits\EmbeddedAdmin;

class SpeciesEmbeddedAdmin extends CoreAdmin
{
    use EmbeddedAdmin;

    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.variety.species';

    protected $baseRouteName = 'admin_sil_variety_speciesembeddedadmin';
    protected $baseRoutePattern = 'sil/variety/speciesembedded';

    public function getExportFields()
    {
        return [
            'name',
            'latin_name',
            'alias',
            'code',
            'species.name',
            'description',
            'parent.name',
            'parent.code',
            'created_at',
            'updated_at',
        ];
    }
}
