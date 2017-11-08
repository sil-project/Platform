<?php

declare(strict_types=1);

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ManufacturingBundle\Admin;

use Blast\Bundle\ResourceBundle\Sonata\Admin\ResourceAdmin;
use Sil\Bundle\ManufacturingBundle\Domain\Generator\BomCodeGeneratorInterface;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class BomAdmin extends ResourceAdmin
{
    protected $baseRouteName = 'admin_manufacturing_bom';
    protected $baseRoutePattern = 'manufacturing/bom';

    /**
     * @var BomCodeGeneratorInterface
     */
    private $bomCodeGenerator;

    /**
     * {@inheritdoc}
     */
    public function prePersist($operation)
    {
        $this->preUpdate($operation);
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        //generate code for the bom
        $code = $this->getBomCodeGenerator()->generate();
        $object->setCode($code);

        parent::preUpdate($object);
    }

    /**
     * @return BomCodeGeneratorInterface
     */
    public function getBomCodeGenerator(): BomCodeGeneratorInterface
    {
        return $this->bomCodeGenerator;
    }

    /**
     * @param BomCodeGeneratorInterface $bomCodeGenerator
     */
    public function setBomCodeGenerator(BomCodeGeneratorInterface $bomCodeGenerator)
    {
        $this->bomCodeGenerator = $bomCodeGenerator;
    }

    public function toString($object)
    {
        return $object->getCode();
    }
}
