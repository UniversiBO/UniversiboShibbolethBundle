<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Http\Logout;
use Symfony\Component\HttpFoundation\Cookie;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ShibbolethLogoutHandler implements LogoutHandlerInterface
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Security\Http\Logout.LogoutHandlerInterface::logout()
     */
    public function logout(Request $request, Response $response,
            TokenInterface $token)
    {
        foreach ($request->cookies->keys() as $key) {
            if (preg_match('/shibsession/', $key)) {
                $response->headers->setCookie(new Cookie($key));
            }
        }

        $request->getSession()->invalidate();

        if($request->query->get('shibboleth')) {
            $greenCheck = '/bundles/universiboshibboleth/images/greencheck.gif';
            $response->headers->set('Location', $request->getBasePath().'$greenCheck);
            $response->setStatusCode(302);
        }
    }
}
