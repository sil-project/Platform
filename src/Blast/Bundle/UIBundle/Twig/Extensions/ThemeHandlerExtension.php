<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\UIBundle\Twig\Extensions;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;

class ThemeHandlerExtension extends \Twig_Extension
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var array
     */
    private $blastUiParameter;

    /**
     * @param Session      $session
     * @param RequestStack $requestStack
     * @param array        $blastUiParameter
     */
    public function __construct(Session $session, RequestStack $requestStack, array $blastUiParameter)
    {
        $this->session = $session;
        $this->requestStack = $requestStack;
        //convert array to stdClass using json_decode(json_encode(...))
        $this->blastUiParameter = json_decode(json_encode($blastUiParameter));
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'blastui_handle_theme',
                [$this, 'handleTheme']
            ),
        ];
    }

    public function handleTheme()
    {
        $availableThemes = $this->blastUiParameter->themes;
        $defaultTheme = $this->blastUiParameter->defaultTheme;
        $sessionTheme = $this->session->get('theme', $defaultTheme);
        $theme = $this->requestStack->getMasterRequest()->query->get('theme', $sessionTheme);

        if (!in_array($theme, $availableThemes)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Theme with name « %s » does not exists in configuration. Available themes are : « %s »',
                    $theme, implode(' » , « ', array_values($availableThemes))
                )
            );
        }

        $this->session->set('theme', $theme);
    }
}
