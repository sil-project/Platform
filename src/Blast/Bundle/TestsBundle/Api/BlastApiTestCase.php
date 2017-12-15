<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\TestsBundle\Api;

use PHPUnit\Framework\TestCase;

class BlastApiTestCase extends TestCase
{
    /********** Api Tools **********/
    protected $token;
    protected $identifier = '';
    protected $secret = '';

    /********** Test Api **********/
    protected $base;
    protected $result;
    protected $show_results;
    protected $show_curl;

    /********** HTTPResult **********/
    protected $data;
    protected $status;
    protected $resource;

    /********** PhpUnit SetUp **********/
    public function setUp()
    {
        global $argv, $argc;

        $optidx = array_search('CONF', $argv);

        if (!$optidx || $argc < $optidx + 3) {
            die("Usage: phpunit tests CONF 'http://my.url.srv' username password\n");
        }

        /* @todo check if base is a valid url */

        $this->base = $argv[$optidx + 1];
        $this->identifier = $argv[$optidx + 2];
        $this->secret = $argv[$optidx + 3];

        if (array_search('SHOW_RESULT', $argv)) {
            $this->show_results = true;
        }

        if (array_search('SHOW_CURL', $argv)) {
            $this->show_curl = true;
        }
    }

    /********** Api Tools **********/
    public function initToken($endpoint, $option, $tokenKey = 'access_token')
    {
        $route = $endpoint . '?' . http_build_query($option);
        $this->request($route);
        $json = $this->getData(true);
        $this->assertArrayHasKey($tokenKey, $json);
        $this->token = $json[$tokenKey];

        return $this;
    }

    /********** Test Api **********/
    public function printResult($endpoint, $action)
    {
        echo "\n\n";

        echo "######## Result ########\n";
        echo "$endpoint | $action | ";
        echo !$this->isSuccess() ? 'ERROR' : 'SUCCESS';
        echo ': HTTP ' . $this->getStatus() . "\n";

        if ($this->show_curl) {
            echo "######## Curl ########\n";
            echo $this->lastCurlEquivalent . "\n";
            $this->lastCurlEquivalent = '';
        }

        if ($this->show_results) {
            echo "######## Data ########\n";
            echo $this->getData() . "\n";
        }

        echo "\n\n";

        return $this;
    }

    private function convertUriAbsolute($uri)
    {
        if (!$this->base) {
            return $uri;
        }

        if (strpos($uri, 'http://') === 0 || strpos($uri, 'https://') === 0) {
            return $uri;
        }

        return $this->base . $uri;
    }

    /**
     * Sends a HTTP request using CURL.
     *
     * @param $uri    string   absolute or relative (to $this->base) URI
     * @param $data   array    data to be sent
     * @param $format   string   can be "json" or "urlencoded"
     **/
    public function request($uri, $method = 'GET', array $data = [], $format = 'json')
    {
        $method = strtoupper($method);
        $absoluteUri = $this->convertUriAbsolute($uri);

        $ch = curl_init($absoluteUri);

        if ($data) {
            switch ($format) {
                case 'urlencoded':
                    $encodedData = http_build_query($data);
                    break;
                default:
                    $encodedData = json_encode($data);
                    break;
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        }

        $headers = $format == 'json' ? ['Content-Type: application/json'] : [];
        if ($this->token) {
            $headers[] = 'Authorization: Bearer ' . $this->token;
        }

        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST  => $method,
        ]);

        if ($this->show_curl) {
            $h = implode('" -H "', $headers);
            $this->lastCurlEquivalent = sprintf(
                '$ curl -k "%s" -H "%s" -X %s %s',
                str_replace(
                    ['[', ']', ' '],
                    ['\\[', '\\]', '%20'],
                    $absoluteUri
                ),
                $h,
                $method,
                $data ? "--data '" . $encodedData . "'" : ''
            );
        }

        $this->getHTTPResult($ch);

        curl_close($ch);

        $this->printResult($absoluteUri, $method);
    }

    /********** HTTPResult **********/

    public function getHTTPResult($curlResource)
    {
        $this->resource = $curlResource;
        $this->data = curl_exec($curlResource);
        $this->status = curl_getinfo($curlResource, CURLINFO_HTTP_CODE);
    }

    public function getCurlResource()
    {
        return $this->resource;
    }

    public function getData($json = false)
    {
        return $json ? json_decode($this->data, true) : $this->data;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function assertStatus($wantedStatus = 200)
    {
        return $this->assertEquals($wantedStatus, $this->getStatus());
    }

    public function isSuccess()
    {
        return $this->status < 400;
    }

    public function assertSuccess()
    {
        return $this->assertTrue($this->isSuccess());
    }

    public function __toString()
    {
        return is_array($this->data) ? json_encode($this->data, JSON_PRETTY_PRINT) : $this->data;
    }

    /**
     * Returns one entity from a list of entities.
     *
     * $i   integer if < 0, it means that we are expecting a random result
     *
     * @todo : maybe add ['_embedded']['items'] as param (or not)
     */
    public function getOneFromList($i = -1)
    {
        $data = $this->getData(true)['_embedded']['items'];
        if (count($data) == 0) {
            return [];
        }

        return $i < 0 ? $data[rand(0, count($data) - 1)] : $data[$i];
    }
}
