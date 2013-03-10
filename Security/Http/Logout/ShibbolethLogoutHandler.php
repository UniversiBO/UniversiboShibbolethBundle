<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Http\Logout;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Universibo\Bundle\ShibbolethBundle\Http\Cookie\CookieCleaner;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ShibbolethLogoutHandler implements LogoutHandlerInterface
{
    /**
     * Router
     *
     * @var RouterInterface
     */
    private $router;

    /**
     * Cookie cleaner
     *
     * @var CookieCleaner
     */
    private $cleaner;

    /**
     * Constructor
     *
     * @param RouterInterface $router
     * @param CookieCleaner   $cleaner
     */
    public function __construct(RouterInterface $router, CookieCleaner $cleaner)
    {
        $this->router = $router;
        $this->cleaner = $cleaner;
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Security\Http\Logout.LogoutHandlerInterface::logout()
     */
    public function logout(Request $request, Response $response,
            TokenInterface $token)
    {
        $this->cleaner->clean($request, $response);

        if ($request->query->get('shibboleth')) {
            $route = 'universibo_shibboleth_greencheck';
            $response->headers->set('Location', $this->router->generate($route, array(), true));
            $response->setStatusCode(302);
        }

        $request->getSession()->set('shibbolethClaims', array());
    }
}
