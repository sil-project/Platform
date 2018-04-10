<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\User\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManager;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Core\User\UserChecker;
use Sil\Component\User\Model\User;
use Sil\Component\User\Tests\Unit\Provider\InMemoryUserProvider;
use Sil\Component\User\Role\RoleHierarchy;
use Sil\Component\User\Tests\Unit\Repository\RoleRepository;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class AuthorizationTest extends TestCase
{
    public function test_successful_authorization()
    {
        $decisionManager = $this->getAccessDecisionManager();
        $token = $this->getAuthenticatedToken(['ROLE_ADMIN']);

        $this->assertTrue($decisionManager->decide($token, ['ROLE_ADMIN']));
    }

    public function test_failed_authorization()
    {
        $decisionManager = $this->getAccessDecisionManager();
        $token = $this->getAuthenticatedToken(['ROLE_USER']);

        $this->assertFalse($decisionManager->decide($token, ['ROLE_ADMIN']));
        $this->assertTrue($token->isAuthenticated());
    }

    public function test_successful_role_hierarchy_based_authorization()
    {
        $decisionManager = $this->getAccessDecisionManager();
        $token = $this->getAuthenticatedToken(['ROLE_ADMIN']);

        $this->assertTrue($decisionManager->decide($token, ['ROLE_INTERN']));
    }

    private function getAccessDecisionManager()
    {
        $repository = new RoleRepository(User::class);
        $hierarchy = new RoleHierarchy($repository);
        $voter = new RoleHierarchyVoter($hierarchy);

        return new AccessDecisionManager([$voter]);
    }

    private function getAuthenticatedToken(array $roles)
    {
        /* @todo: factorize this, with the same method in AuthenticationTest.php (Maybe in Tests Bundle with also the in memory user provider )*/
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 5000);

        $user = new User();
        $user->setUsername('admin');
        $password = $encoder->encodePassword('foo', $user->getSalt());
        $user->setPassword($password);

        foreach ($roles as $role) {
            $user->addRole($role);
        }
        $user->enable();

        $encoderFactory = new EncoderFactory([User::class => $encoder]);
        $userProvider = new InMemoryUserProvider([$user]);
        $userChecker = new UserChecker();
        $authProviders = [new DaoAuthenticationProvider($userProvider, $userChecker, User::class, $encoderFactory)];
        $authManager = $authManager = new AuthenticationProviderManager($authProviders);

        $token = new UsernamePasswordToken('admin', 'foo', User::class);

        return $authManager->authenticate($token);
    }
}
