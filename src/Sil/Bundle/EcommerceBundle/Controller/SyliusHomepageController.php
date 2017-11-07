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

namespace Librinfo\EcommerceBundle\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;

class SyliusHomepageController
{
    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @var string
     */
    private $fallbackChannelCode;

    /**
     * @var TwigEngine
     */
    private $templating;

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $response = new Response();
        try {
            $this->channelContext->getChannel();
        } catch (ChannelNotFoundException $e) {
            $response->headers->setCookie(new Cookie('_channel_code', $this->fallbackChannelCode));
            $request->query->set('_channel_code', $this->fallbackChannelCode);
        }

        return $this->templating->renderResponse('@SyliusShop/Homepage/index.html.twig', [], $response);
    }

    /**
     * @param ChannelContextInterface channelContext
     */
    public function setChannelContext(ChannelContextInterface $channelContext)
    {
        $this->channelContext = $channelContext;
    }

    /**
     * @param string fallbackChannelCode
     */
    public function setFallbackChannelCode($fallbackChannelCode)
    {
        $this->fallbackChannelCode = $fallbackChannelCode;
    }

    /**
     * @param TwigEngine templating
     */
    public function setTemplating(TwigEngine $templating)
    {
        $this->templating = $templating;
    }
}
