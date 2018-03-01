<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

// @group route
use Codeception\Util\HttpCode;

$I = new WebGuy($scenario);
$I->amGoingTo('Test All Route');

$allCode = array();
$allLink = array();

function dumpvar($var)
{
    fwrite(STDERR, print_r($var, true));
    fwrite(STDERR, print_r("\n", true));
}

function doLogin($webGuy)
{
    $webGuy->amGoingTo('Login');
    //## LOGIN ####
    $webGuy->amOnPage('/lisem/login');
    $webGuy->fillField("//input[@id='_username']", 'lisem@lisem.eu');
    $webGuy->fillField("//input[@id='_password']", 'lisem');
    $webGuy->click("//button[@type='submit']");
}

function stdCheck($webGuy, $url, &$allCode)
{
    $allCode[$url] = 'OK';

    $webGuy->cantSee('Stack Trace'); /* :) :) we hope so */
    $webGuy->cantSeeInSource('<div class="exceptionContainer">'); /* :) :) we hope so too */

    // if ($webGuy->isPhpBrowser()) {
    // $webGuy->canseeResponseCodeIs(HttpCode::OK);
    $allCode[$url] = $webGuy->getStatusCode();
    // }
    //   dumpvar($allCode);

    // if ($webGuy->isWebDriver()) {
    // $webGuy->waitForText('Libre', 10); // secs
    // }

    //
    // $webGuy->seeResponseCodeIs(HttpCode::OK); /* does not work with selenium */
}

function checkPage($webGuy, $urlPage, &$linkList, &$allCode)
{
    $webGuy->amGoingTo('Test Route: ' . $urlPage);
    $webGuy->amOnPage($urlPage);
    stdCheck($webGuy, $urlPage, $allCode);

    $allLink = $webGuy->grabMultiple('a', 'href');
    $allShow = preg_grep('/show$/', $allLink);
    $allEdit = preg_grep('/edit$/', $allLink);

    /* @todo : factorize this + should use a random (not always the first one)  */
    $cpt = 3;
    foreach ($allShow as $oneShow) {
        array_push($linkList, $oneShow);
        if ($cpt-- == 0) {
            break;
        }
    }

    $cpt = 3;
    foreach ($allEdit as $oneEdit) {
        array_push($linkList, $oneEdit);
        if ($cpt-- == 0) {
            break;
        }
    }
    //dump($linkList);
    //return $linkList;
}

doLogin($I);

//## Get Some Symfony Service ####
$curRouter = $I->grabServiceFromContainer('router');
//$curTranslator = $I->grabServiceFromContainer('translator');
//$curCatalogue = $curTranslator->getCatalogue();
/* As we don't use the good locale ... */
// if (empty($curCatalogue->all('messages'))) {
//     $curCatalogue = $curCatalogue->getFallbackCatalogue();
// }
// $curMessage = $curCatalogue->all('messages');

$rtlim = 10;

foreach ($curRouter->getRouteCollection() as $curRoute) {
    $routePath = $curRoute->getPath();
    $routeDefault = $curRoute->getDefaults();
    $routeMethod = $curRoute->getMethods();
    $curAction = basename($routePath); /* ugly way to get the action */

    /* Select only usefull route (or not) */
    if (preg_match('/lisem|librinfo/', $routePath)
        && !preg_match('/{|}/', $routePath)
        && !preg_match('/login/', $routePath)
        && !preg_match('/searchindexentity/', $routePath)
    ) {
        /* Check if we can GET (or not) */
        if (empty($routeMethod)
            || in_array('GET', $routeMethod)) {
            /* Are we in a sonata admin ? */
            if (isset($routeDefault['_controller'])
                && array_key_exists('_sonata_admin', $routeDefault)) {
                // $curAdmin = $I->grabServiceFromContainer($routeDefault['_sonata_admin']);
                $curMapper = null;
                switch ($curAction) {
                    case 'list':
                        $curMapper = 'list';
                        break;
                    case 'show':
                        $curMapper = 'show';
                        break;
                    case 'create':
                    case 'edit':
                        $curMapper = 'form';
                        break;
                }
                if (isset($curMapper)) {
                    //$curLabel = $curAdmin->getLabelTranslatorStrategy()->getLabel('', '', '');
                    checkPage($I, $routePath, $allLink, $allCode);
                    //                    array_push($allLink, checkPage($I, $routePath));

                    // $libKeys = preg_grep('/^' . $curLabel . '/', array_keys($curMessage));

                    // foreach ($libKeys as $curKeys) {
                    //     $I->cantSeeInSource($curKeys); /* We should not see label key */
                    // }
                    //     if ($rtlim-- == 0) {
                    //   break;
                    //}
                }
            }
        }
    }
}

// $uniqLink = array_unique($allLink);

// foreach ($uniqLink as $curLink) {
//     //dump($curLink);
//     $I->amGoingTo('Test Link: ' . $curLink);
//     $I->amOnUrl($curLink);
//     stdCheck($I, $curLink, $allCode);

//     $allLink = $I->grabMultiple('a', 'href');
//     //    $allAnchor =  preg_grep('/^#/', $allLink);

//     //dump($allAnchor);
// }

dumpvar($allCode);
$I->amGoingTo('The End Flush STDERR ');
