<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Http\Logout;
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
            if (preg_match('/^shib/', $key)) {
                $response->headers->setCookie(new Cookie($key));
            }
        }

        $request->getSession()->invalidate();
    }
}
