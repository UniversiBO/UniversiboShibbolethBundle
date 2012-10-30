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
     * @var RouterInterface 
     */
    private $router;
    
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
    
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

        if ($request->query->get('shibboleth')) {
            $route = 'universibo_shibbolet_greencheck';
            $response->headers->set('Location', $this->router->generate($route, array(), true));
            $response->setStatusCode(302);
        }

        $request->getSession()->set('shibbolethClaims', array());
    }
}
