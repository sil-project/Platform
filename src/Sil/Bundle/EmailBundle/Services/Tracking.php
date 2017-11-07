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

namespace Sil\Bundle\EmailBundle\Services;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class Tracking
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Adds tracking to an Email.
     *
     * @param string $content
     * @param string $address
     * @param string $emailId
     *
     * @return string email content updated with tracking info
     */
    public function addTracking($content, $address, $emailId)
    {
        $updatedContent = $this->processLinks($content, $address, $emailId) . $this->getTracker($address, $emailId);

        return $updatedContent;
    }

    /**
     * Parse links in the content and redirect them to track clicks.
     *
     * @param type $content
     * @param type $address
     * @param type $emailId
     *
     * @return type
     */
    private function processLinks($content, $address, $emailId)
    {
        $links = array();

        preg_match_all('!<a\s(.*)href="(https{0,1}://.*)"(.*)>(.*)</a>!U', $content, $links, PREG_SET_ORDER);

        foreach ($links as $link) {
            $url = $this->router->generate('librinfo_email.track_links', [
                'emailId'     => $emailId,
                'recipient'   => base64_encode($address),
                'destination' => base64_encode($link[2]),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
            );

            $content = str_replace($link[0],
                    '<a ' . $link[1] .
                    'href="' . $url . '"' .
                    $link[3] . '>' .
                    $link[4] . '</a>',
                $content
            );
        }

        return $content;
    }

    /**
     * Add 1*1 img to track opened emails.
     *
     * @param type $address
     * @param type $emailId
     *
     * @return type
     */
    private function getTracker($address, $emailId)
    {
        $url = $this->router->generate(
            'librinfo_email.track_opens',
            [
                'emailId'   => $emailId,
                'recipient' => base64_encode($address),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return '<img src="' . $url . '.png" alt="" width="1" height="1"/>';
    }
}
