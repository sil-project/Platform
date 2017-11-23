<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EmailBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sil\Bundle\EmailBundle\Entity\EmailTemplate;
use Sil\Bundle\MediaBundle\Entity\FileInterface;

class AjaxController extends Controller
{
    /**
     * @param string $templateId
     *
     * @return Response The template content that will be inserted into the main content
     */
    public function getEmailTemplateAction($templateId)
    {
        $repo = $this->getDoctrine()->getRepository(EmailTemplate::class);
        $template = $repo->find($templateId);

        return new Response($template->getContent(), 200);
    }

    /**
     * @param string $fileId
     *
     * @return Response img tag that will be embedded into the main content
     */
    public function addToContentAction($fileId)
    {
        $repo = $this->getDoctrine()->getRepository(FileInterface::class);

        $file = $repo->find($fileId);

        return new Response($this->renderView('SilMediaBundle:Generator:img_tag.html.twig', ['img' => $file]));
    }
}
