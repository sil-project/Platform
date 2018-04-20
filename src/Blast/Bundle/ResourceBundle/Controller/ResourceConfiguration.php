<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\ResourceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Blast\Component\Resource\Metadata\Metadata;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class ResourceConfiguration
{
    /**
     * @var Metadata
     */
    protected $resourceMetadata;

    /**
     * @var Request
     */
    protected $request;

    public function __construct(Metadata $resourceMetadata, Request $request)
    {
        $this->request = $request;
        $this->resourceMetadata = $resourceMetadata;
    }

    public function getTemplate($action)
    {
        $base = $this->resourceMetadata->getRouting()->getTemplatePath();

        return sprintf('%s/%s.html.twig', $base, $action);
    }

    public function getViewType()
    {
        return $this->resourceMetadata->getClassMap()->getView();
    }

    public function getFormType()
    {
        return $this->resourceMetadata->getClassMap()->getForm();
    }
}
