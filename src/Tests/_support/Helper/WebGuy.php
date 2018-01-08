<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class WebGuy extends \Codeception\Module
{
    /* @todo : should be moved in TestBundle */

    public function isLoaded($moduleName)
    {
        /* @todo: find a clean way to check if module is enable or not */

        //   if ($this->getModule($moduleName)) {
        return true;
        //}
        // return false;
    }

    public function isWebDriver()
    {
        return false;
        // return $this->isLoaded('WebDriver');
    }

    public function isPhpBrowser()
    {
        return true;
        //return $this->getModule('PhpBrowser');
    }

    public function getStatusCode()
    {
        $response = $this->getModule('PhpBrowser')->client->getInternalResponse();
        if (method_exists($response, 'getStatus')) {
            return $response->getStatus();
        }
        if (method_exists($response, 'getStatusCode')) {
            return $response->getStatusCode();
        }

        if ($this->isPhpBrowser()) {
            // $status = $this->getModule('PhpBrowser')->getResponseStatusCode();
            // return  $this->getModule('PhpBrowser')->getRunningClient()->getInternalResponse()->getHeader('Response', true);
            // return  $this->getModule('PhpBrowser')->client->getInternalResponse()->getHeader('Response');
            // return $this->getModule('PhpBrowser')->headers;
            // return $status;
        }

        return null;
    }

    public function getResponseContent()
    {
        if ($this->isPhpBrowser()) {
            return $this->getModule('PhpBrowser')->_getResponseContent();
        }

        return null;
    }
}
