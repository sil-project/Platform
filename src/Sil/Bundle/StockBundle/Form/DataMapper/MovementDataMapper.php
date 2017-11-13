<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Form\DataMapper;

use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Sil\Bundle\StockBundle\Domain\Factory\MovementFactory;
use Sil\Bundle\StockBundle\Domain\Generator\MovementCodeGenerator;

/**
 * Description of OperationDataMapper.
 *
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class MovementDataMapper extends PropertyPathMapper
{
    public function mapDataToForms($data, $forms)
    {
        return parent::mapDataToForms($data, $forms);
    }

    public function mapFormsToData($forms, &$data)
    {
        $factory = new MovementFactory(new MovementCodeGenerator());
        $forms = iterator_to_array($forms);

        if (null === $data) {
            $item = $forms['stockItem']->getData();
            $qty = $forms['qty']->getData();

            $data = $factory->createDraft($item, $qty);
        } else {
            parent::mapFormsToData($forms, $data);
        }
    }
}
