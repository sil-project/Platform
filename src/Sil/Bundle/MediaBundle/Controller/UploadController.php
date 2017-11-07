<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\MediaBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sil\Bundle\MediaBundle\Entity\File;
use Symfony\Component\EventDispatcher\GenericEvent;
use Sil\Bundle\MediaBundle\Events\UploadControllerEventListener;

class UploadController extends Controller
{
    /**
     * Upload.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function uploadAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $file = $request->files->get('file');

        $new = new File();
        $new->setFile($file);
        $new->setMimeType($file->getMimeType());
        $new->setName($file->getClientOriginalName());
        $new->setSize($file->getClientSize());
        $new->setOwned(false);

        $manager->persist($new);
        $manager->flush();

        return new Response($new->getId(), 200);
    }

    /**
     * Removal.
     *
     * @param string $fileId
     *
     * @return Response
     */
    public function removeAction($fileId = null)
    {
        if (!$fileId) {
            return new Response('Please provide a file id', 500);
        }

        $manager = $this->getDoctrine()->getManager();
        $repo = $this->getDoctrine()->getRepository('SilMediaBundle:File');

        $file = $repo->findOneBy([
            'id'    => $fileId,
        ]);

        if ($file !== null) {
            if ($file->isOwned()) {
                $dispatcher = $this->get('event_dispatcher');
                $event = new GenericEvent($file);
                $dispatcher->dispatch(UploadControllerEventListener::REMOVE_ENTITY, $event);
            }

            $name = '' . $file->getName();
            $manager->remove($file);
            $manager->flush();

            return new Response($name . ' removed successfully', 200);
        } else {
            return new Response('the file cannot be removed', 401);
        }
    }

    /**
     * Retrieves.
     *
     * @param Request $request
     *
     * @return Response files converted to json array
     */
    public function loadAction(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository('SilMediaBundle:File');
        $files = [];

        foreach ($request->get('load_files') as $key => $id) {
            $file = null;

            $dispatcher = $this->get('event_dispatcher');

            $event = new GenericEvent(
                [
                    'request' => $request,
                    'context' => [
                        'key'  => $key,
                        'id'   => $id,
                        'file' => $file,
                    ],
                ], [
                    'file'  => null,
                    'files' => $files,
                ]
            );
            $dispatcher->dispatch(UploadControllerEventListener::PRE_GET_ENTITY, $event);

            $file = $event->getArgument('file');

            if ($file) {
                $file->setFile($file->getBase64File());
            }

            $event = new GenericEvent(
                [
                    'request' => $request,
                    'context' => [
                        'key'  => $key,
                        'id'   => $id,
                        'file' => $file,
                    ],
                ], [
                    'file'  => $file,
                    'files' => $files,
                ]
            );
            $dispatcher->dispatch(UploadControllerEventListener::POST_GET_ENTITY, $event);

            $file = $event->getArgument('file');

            if ($file) {
                $files[] = $file;
            }
        }

        return new JsonResponse($files, 200);
    }
}
