<?php

namespace Universibo\Bundle\ShibbolethBundle\Tests\Security\Http;

use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Universibo\Bundle\ShibbolethBundle\Http\Cookie\CookieCleaner;
use Universibo\Bundle\ShibbolethBundle\Security\Http\Logout\ShibbolethLogoutHandler;

class ShibbolethLogoutHandlerTest extends PHPUnit_Framework_TestCase
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
     * Logout handler
     *
     * @var ShibbolethLogoutHandler
     */
    private $handler;

    protected function setUp()
    {
        $this->router  = $this->getMock('Symfony\\Component\\Routing\\RouterInterface');
        $this->cleaner = $this->getMock('Universibo\\Bundle\\ShibbolethBundle\\Http\\Cookie\\CookieCleaner');
        $this->handler = new ShibbolethLogoutHandler($this->router, $this->cleaner);
    }

    public function testLogout()
    {
        $request = new Request();
        $session = $this->getMock('Symfony\\Component\\HttpFoundation\\Session\\SessionInterface');
        $request->setSession($session);

        $session
            ->expects($this->once())
            ->method('set')
            ->with($this->equalTo('shibbolethClaims'), $this->equalTo(array()))
        ;

        $response = new Response();

        $this
            ->cleaner
            ->expects($this->once())
            ->method('clean')
            ->with($this->equalTo($request), $this->equalTo($response))
        ;

        $this->handler->logout($request, $response, new UsernamePasswordToken('username', 'password', 'provider'));
    }
}
