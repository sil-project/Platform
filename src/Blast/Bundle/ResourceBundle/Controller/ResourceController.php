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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Blast\Component\Resource\Repository\ResourceRepositoryInterface;

/**
  * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
  */
 class ResourceController extends Controller
 {
     const SHOW = 'show';
     const LIST = 'list';
     const CREATE = 'create';
     const UPDATE = 'update';
     const DELETE = 'delete';

     /**
      * @var ViewHandlerInterface
      */
     protected $viewHandler;
     /**
      * @var ResourceRepositoryInterface
      */
     protected $resourceRepository;

     public function __construct(
       ViewHandlerInterface $viewHandler,
       ResourceRepositoryInterface $repository
       ) {
         $this->viewHandler = $viewHandler;
         $this->resourceRepository = $repository;
     }

     public function listAction(Request $request): Response
     {
     }

     public function showAction(Request $request): Response
     {
         $view = View::create($resource);

         if ($configuration->isHtmlRequest()) {
             $view
                 ->setTemplate($this->getTemplate(self::SHOW . '.html'))
                 ->setTemplateVar($this->metadata->getName())
                 ->setData([
                     'configuration'            => $configuration,
                     'metadata'                 => $this->metadata,
                     'resource'                 => $resource,
                 ]);
         }

         return $this->viewHandler->handle($view);
     }

     public function createAction(Request $request): Response
     {
     }

     public function updateAction(Request $request): Response
     {
     }

     public function deleteAction(Request $request): Response
     {
     }
 }
