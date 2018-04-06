<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\UserBundle\Controller;

use Sil\Bundle\UserBundle\Form\Type\UserLoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/*
Ref:
 http://symfony.com/doc/3.4/security/form_login_setup.html
+ Code Sylius Security Controller
*/

class SecurityController extends Controller
{
    /**
     * Login form action.
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $options = $request->attributes->get('_sil');
        $formType = UserLoginType::class;
        $form = $this->get('form.factory')->createNamed('', $formType);

        return $this->render($options['template'], array(
            'form'          => $form->createView(),
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * Login check action. This action should never be called.
     */
    public function checkAction(Request $request): Response
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall.');
    }

    /**
     * Logout action. This action should never be called.
     */
    public function logoutAction(Request $request): Response
    {
        throw new \RuntimeException('You must configure the logout path to be handled by the firewall.');
    }
}
