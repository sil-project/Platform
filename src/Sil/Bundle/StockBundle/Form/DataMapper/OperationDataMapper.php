<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Form\DataMapper;

use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Sil\Bundle\StockBundle\Domain\Factory\OperationFactory;
use Sil\Bundle\StockBundle\Domain\Generator\OperationCodeGenerator;

/**
 * Description of OperationDataMapper.
 *
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class OperationDataMapper extends PropertyPathMapper
{
    public function mapDataToForms($data, $forms)
    {
        return parent::mapDataToForms($data, $forms);
    }

    public function mapFormsToData($forms, &$data)
    {
        $factory = new OperationFactory(new OperationCodeGenerator());
        $forms = iterator_to_array($forms);

        if (null === $data) {
            $srcLocation = $forms['destLocation']->getData();
            $destLocation = $forms['srcLocation']->getData();

            $data = $factory->createDraft($srcLocation, $destLocation);
        } else {
            parent::mapFormsToData($forms, $data);
        }
    }
}
