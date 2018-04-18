<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use Blast\Bundle\TestsBundle\Codeception\Step\Acceptance\SilUser as SilUserTester;

/**
 * @group user
 * @group all
 */
class UserCest
{
    public function testSilUser(SilUserTester $sil)
    {
        $user = [
            'name'     => $sil->getRandName(true) . '-name',
            'email'    => $sil->getRandName(true) . '@sil.eu',
            'password' => 'pwd_' . $sil->getRandNbr(true),
        ];

        $sil->loginSil('sil@sil.eu', 'sil');
        $sil->createUser($user['name'], $user['email'], $user['password']);
        $sil->logoutSil();
        $sil->loginSil($user['name'], $user['password']);
        $sil->loggedAs($user['name']);
        $sil->logoutSil();
    }
}
