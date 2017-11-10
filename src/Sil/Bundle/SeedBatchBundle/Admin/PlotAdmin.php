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

use Sonata\CoreBundle\Validator\ErrorElement;
use Blast\Bundle\CoreBundle\Admin\CoreAdmin;
use Blast\Bundle\CoreBundle\Admin\Traits\HandlesRelationsAdmin;
use Sil\Bundle\SeedBatchBundle\Entity\Plot;

class PlotAdmin extends CoreAdmin
{
    use HandlesRelationsAdmin;

    /**
     * @param Plot   $plot
     * @param string $property (not used)
     *
     * @return string
     */
    public static function autocompleteToString(Plot $plot, $property)
    {
        return sprintf('%s [%s] [%s]', $plot->getName(), $plot->getCode(), $plot->getProducer()->getName());
    }

    /**
     * @param ErrorElement $errorElement
     * @param Plot         $object
     *
     * @deprecated this feature cannot be stable, use a custom validator,
     *             the feature will be removed with Symfony 2.2
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        $this->validateCode($errorElement, $object);
    }

    /**
     * Plot code validator.
     *
     * @param ErrorElement $errorElement
     * @param Plot         $object
     */
    public function validateCode(ErrorElement $errorElement, $object)
    {
        $code = $object->getCode();
        $registry = $this->getConfigurationPool()->getContainer()->get('blast_core.code_generators');
        $codeGenerator = $registry->getCodeGenerator(Plot::class);
        if (!empty($code) && !$codeGenerator->validate($code)) {
            $msg = 'Wrong format for plot code. It shoud be: ' . $codeGenerator::getHelp();
            $errorElement
                ->with('code')
                    ->addViolation($msg)
                ->end()
            ;
        }
    }
}
