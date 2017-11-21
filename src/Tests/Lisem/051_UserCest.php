<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @group user
 * @group all
 */
use Step\Acceptance\SonataSyliusUser as SonataSyliusUserTester;
use Step\Acceptance\Ecommerce as EcommerceTester;
use Step\Acceptance\Sylius as SyliusTester;

class CreateUserCest
{
    public function testLisemUser(SonataSyliusUserTester $lisem)
    {
        $userList = [];
        $cpt = 2; // + $lisem->getRandNbr(true) % 2;
        // see https://github.com/Codeception/Codeception/issues/4008
        while ($cpt-- > 0) {
            $userList[] = ['name' => $lisem->getRandName(true) . '-name',  'email' => $lisem->getRandName(true) . '@lisem.eu', 'password' => 'pwd_' . $lisem->getRandNbr(true)];
        }

        foreach ($userList as $curUser) {
            $lisem->loginLisem();
            $lisem->createUser($curUser['name'], $curUser['email'], $curUser['password']);
            $lisem->logoutLisem();
            $lisem->loginLisem($curUser['email'], $curUser['password']);
            $lisem->loggedAs($curUser['name']);
            $lisem->logoutLisem();
        }
    }

    public function testSyliusUser(EcommerceTester $lisem, SyliusTester $sylius)
    {
        $userList = [];
        $cpt = 2; // + $lisem->getRandNbr(true) % 2;
        // see https://github.com/Codeception/Codeception/issues/4008
        while ($cpt-- > 0) {
            $userList[] = ['name' => $lisem->getRandName(true) . '-name',  'email' => $lisem->getRandName(true) . '@lisem.eu', 'password' => 'pwd_' . $lisem->getRandNbr(true)];
        }

        foreach ($userList as $curUser) {
            $sylius->createAccount($curUser['name'], $curUser['email'], $curUser['password']);
            $lisem->loginLisem();
            $lisem->activeAccount($curUser['email']);
            $sylius->loginSylius($curUser['email'], $curUser['password']);
            $sylius->logoutSylius();
        }
    }
}
