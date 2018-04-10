<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\ResourceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View as FOSView;
use FOS\RestBundle\View\ViewHandlerInterface;
use Blast\Component\Resource\Metadata\MetadataInterface;
use Blast\Component\Resource\Repository\ResourceRepositoryInterface;
use Blast\Component\Resource\Actions;
use Blast\Component\Resource\Model\ResourceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Glenn Cavarlé <glenn.cavarle@libre-informatique.fr>
 */
class ResourceController extends Controller
{
    /**
     * @var MetadataInterface
     */
    protected $resourceMetadata;

    /**
     * @var ResourceRepositoryInterface
     */
    protected $resourceRepository;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var ViewHandlerInterface
     */
    protected $viewHandler;

    public function __construct(
       MetadataInterface $resourceMetadata,
       ResourceRepositoryInterface $resourceRepository,
       EventDispatcherInterface $eventDispatcher,
       ViewHandlerInterface $viewHandler
       ) {
        $this->resourceMetadata = $resourceMetadata;
        $this->resourceRepository = $resourceRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->viewHandler = $viewHandler;
    }

    public function showAction(Request $request)
    {
        $config = new ResourceConfiguration($this->resourceMetadata, $request);
        //$this->denyAccessUnlessGranted(Actions::SHOW);

        $resource = $this->findOr404($config, $request);

        //$this->dispatchEvent(ShowResourceEvent::class, $resource);

        $vm = $this->createViewModel($config, $resource);
        $view = $this->createView();

        $view
        ->setTemplate($config->getTemplate(Actions::SHOW))
        ->setData([
          'template' => [
              'logo'  => '',
              'title' => 'SIL Platform',
          ],
          'page' => [
            'header' => [
              'icon'        => 'user',
              'title'       => '...',
              'description' => '...',
            ],
          ],
          'config' => $config,
          'model'  => $vm->createRenderingView(),
        ]);

        return $this->viewHandler->handle($view);
    }

    public function indexAction(Request $request)
    {
    }

    public function createAction(Request $request)
    {
    }

    public function updateAction(Request $request)
    {
    }

    public function deleteAction(Request $request)
    {
    }

    protected function createViewModel($config, $data): ViewModel
    {
        return $data;
    }

    protected function createView(): FOSView
    {
        $view = FOSView::create();

        return $view;
    }

    /**
     * @param string $eventClass
     * @param mixed  $data
     */
    protected function dispatchEvent($eventClass, $data)
    {
        $event = new $eventClass($data);
        $this->eventDispatcher->dispatch($eventClass::NAME, $event);
    }

    protected function findOr404($config, $request): ResourceInterface
    {
        $id = $request->attributes->get('id');
        $resource = $this->resourceRepository->find($id);

        if (null === $resource) {
            throw new NotFoundHttpException(sprintf('The "%s" has not been found', $this->metadata->getName()));
        }

        return $resource;
    }
}
