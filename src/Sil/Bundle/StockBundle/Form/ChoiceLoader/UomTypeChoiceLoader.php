<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\StockBundle\Form\ChoiceLoader;

use Symfony\Component\Form\ChoiceList\Loader\ChoiceLoaderInterface;
use Sil\Bundle\StockBundle\Domain\Repository\UomTypeRepositoryInterface;
use Symfony\Component\Form\ChoiceList\ChoiceListInterface;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;

/**
 * Description of UomTypeChoiceLoader.
 *
 * @author glenn
 */
class UomTypeChoiceLoader implements ChoiceLoaderInterface
{
    /**
     * @param UomTypeRepositoryInterface $manager
     * @param array                      $options
     */
    public function __construct(UomTypeRepositoryInterface $uomTypeRepository)
    {
        $this->uomTypeRepository = $uomTypeRepository;
    }

    //put your code here
    public function loadChoiceList($value = null): ChoiceListInterface
    {
        $uomTypes = $this->uomTypeRepository->findAll();

        return new ArrayChoiceList($uomTypes, function ($ut) {
            $ut->getId();
        });
    }

    public function loadChoicesForValues(array $values, $value = null): array
    {
        throw new \Exception();
        return [];
    }

    public function loadValuesForChoices(array $choices, $value = null): array
    {
        print_r($value);

        return [];
    }
}
