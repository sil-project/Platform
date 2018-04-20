<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UIBundle\Twig\Extensions;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class UrlToolsExtension extends \Twig_Extension
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'url_decode',
                [$this, 'urlDecode'],
                ['is_safe' => ['html']]
            ),
            new \Twig_SimpleFunction(
                'getMasterRequest',
                [$this, 'getMasterRequest'],
                ['is_safe' => ['html']]
            ),
            new \Twig_SimpleFunction(
                'getCleanUri',
                [$this, 'getCleanUri'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * Decodes an url.
     *
     * @param string $urlToDecode
     *
     * @return string
     */
    public function urlDecode(string $urlToDecode): string
    {
        return urldecode($urlToDecode);
    }

    public function getMasterRequest(): Request
    {
        return $this->requestStack->getMasterRequest();
    }

    public function getCleanUri(?Request $request = null): string
    {
        if ($request === null) {
            $request = $this->getMasterRequest();
        }

        return str_replace($request->getBaseUrl(), '', $this->urlDecode($request->getRequestUri()));
    }
}
