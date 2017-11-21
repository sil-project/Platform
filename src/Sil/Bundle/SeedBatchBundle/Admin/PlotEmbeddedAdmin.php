<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SeedBatchBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;

// use Blast\Bundle\CoreBundle\Admin\Traits\EmbeddedAdmin;

class PlotEmbeddedAdmin extends PlotAdmin
{
    // use EmbeddedAdmin;

    /**
     * @var string
     */
    protected $translationLabelPrefix = 'sil.seed_batch.plot';
    protected $baseRouteName = 'admin_sil_seed_batch_plot_embedded';
    protected $baseRoutePattern = 'sil/seedbatch/plot_embedded';

    public function configureFormFields(FormMapper $mapper)
    {
        parent::configureFormFields($mapper);
        $mapper->remove('producer');
    }
}
