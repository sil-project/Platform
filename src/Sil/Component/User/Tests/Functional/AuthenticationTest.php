<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\User\Tests\Functional;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Provider\AnonymousAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Sil\Component\User\Tests\Functional\Provider\InMemoryUserProvider;
use Sylius\Component\User\Model\User;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class AuthenticationTest extends TestCase
{
    public function test_anonymous_authentication()
    {
        $secret = 'd654dfg564s';

        $authProviders = [new AnonymousAuthenticationProvider($secret)];
        $authManager = new AuthenticationProviderManager($authProviders);
        $token = new AnonymousToken($secret, 'foo');

        $token = $authManager->authenticate($token);

        $this->assertTrue($token->isAuthenticated());
    }

    public function test_successful_user_authentication()
    {
        $authManager = $this->getAuthManager();
        $token = new UsernamePasswordToken('admin', 'foo', User::class);

        $token = $authManager->authenticate($token);

        $this->assertEquals($token->getUser()->getUsername(), 'admin');
        $this->assertTrue($token->isAuthenticated());
    }

    public function test_failed_user_authentication()
    {
        $authManager = $this->getAuthManager();
        $token = new UsernamePasswordToken('admin', 'bar', User::class);

        $this->expectException(BadCredentialsException::class);

        $token = $authManager->authenticate($token);
    }

    private function getAuthManager()
    {
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 5000);
        $encoderFactory = new EncoderFactory([User::class => $encoder]);

        $user = new User();
        $user->setUsername('admin');
        $password = $encoder->encodePassword('foo', $user->getSalt());
        $user->setPassword($password);
        $user->enable();

        $userProvider = new InMemoryUserProvider([$user]);

        $userChecker = new UserChecker();

        $authProviders = [new DaoAuthenticationProvider($userProvider, $userChecker, User::class, $encoderFactory)];

        return new AuthenticationProviderManager($authProviders);
    }
}
