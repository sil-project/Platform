<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\TestsBundle\Functional;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

trait BlastWebTestCaseTrait
{
    /**
     * @var Client
     */
    public $client;

    /**
     * @var ContainerInterface
     */
    public $container;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->container = static::$kernel->getContainer();
        $this->logIn();
    }

    private function logIn()
    {
        /* https://symfony.com/doc/3.4/testing/http_authentication.html */
        $session = $this->client->getContainer()->get('session');

        /* @todo: Not so clean, should be found in a config file ... */
        $firewall = 'sil'; //'secured_area';
        $username = 'sil@sil.eu';
        $password = 'sil';

        $token = new UsernamePasswordToken($username, $password, $firewall, array('ROLE_USER'));
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    protected function goToRoute(string $routeName, array $routeParams = [], ?string $method = 'GET'): Crawler
    {
        $uri = $this->container->get('router')->generate($routeName, $routeParams);

        return $this->goToUri($uri, $method);
    }

    protected function goToUri(string $uri, ?string $method = 'GET'): Crawler
    {
        $crawler = $this->client->request($method, $uri);

        if ($this->client->getResponse()->isRedirect()) {
            $crawler = $this->client->followRedirect();
        }

        return $crawler;
    }

    protected function runForRoute(string $routeName, array $routeParams, string $pageTitle)
    {
        /* @todo: manage route if user is not logged */

        $optionTypeListTitle = $this->container->get('translator')->trans($pageTitle);

        $crawler = $this->goToRoute($routeName, $routeParams);

        $this->assertEquals(
            $optionTypeListTitle,
            trim(preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $crawler->filter('h1.ui.header > div.content')->html()))
        );
    }
}
