<?php

namespace Universibo\Bundle\ShibbolethBundle\Tests\Security\Authentication\Token;

use LogicException;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Universibo\Bundle\ShibbolethBundle\Controller\SecurityController;

class SecurityControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Kernel
     * @var KernelInterface
     */
    private $kernel;

    /**
     * Security Context
     *
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * Router
     *
     * @var RouterInterface
     */
    private $router;

    /**
     * @var SecurityController
     */
    private $controller;

    protected function setUp()
    {
        $this->kernel          = $this->getMock('Symfony\\Component\\HttpKernel\\KernelInterface');
        $this->securityContext = $this->getMock('Symfony\\Component\\Security\\Core\\SecurityContextInterface');
        $this->router          = $this->getMock('Symfony\\Component\\Routing\\RouterInterface');
    }

    private function createController($environment = 'prod')
    {
        $this
            ->kernel
            ->expects($this->once())
            ->method('getEnvironment')
            ->will($this->returnValue($environment))
        ;

        $this->controller = new SecurityController($this->kernel, $this->securityContext,
                $this->router, 'main', 'homepage', 'http://www.google.com/');

        return $this->controller;
    }

    public function testGreenCheckAction()
    {
        $response = $this->createController()->greenCheckAction();

        $this->assertEquals(200, $response->getStatusCode(), 'Status code');
        $this->assertEquals('image/gif', $response->headers->get('Content-type'), 'Content type');
        $this->assertEquals('6953796741f99a1062cf0f70b5ed2a2b2037a3d2', sha1($response->getContent()), 'Content hash');
    }

    /**
     * @expectedException LogicException
     */
    public function testLogoutThrowsLogicException()
    {
        $this->createController()->logoutAction();
    }

    public function testPrelogoutAnonymousWreply()
    {
        $controller = $this->createController();
        $request = new Request();
        $request->query->set('wreply', $url = 'http://www.google.it/');

        $response = $controller->prelogoutAction($request);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($url, $response->headers->get('Location'));
    }

    public function testPrelogoutAnonymousNoWreply()
    {
        $controller = $this->createController();
        $request = new Request();

        $url = '/';

        $this
            ->router
            ->expects($this->once())
            ->method('generate')
            ->with($this->equalTo('homepage'))
            ->will($this->returnValue($url))
        ;

        $response = $controller->prelogoutAction($request);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($url, $response->headers->get('Location'));
    }

    public function testPrelogoutAuthenticated()
    {
        $controller = $this->createController();
        $request = new Request();

        $url = '/';

        $this
            ->router
            ->expects($this->once())
            ->method('generate')
            ->with($this->equalTo('homepage'))
            ->will($this->returnValue($url))
        ;

        $this
            ->securityContext
            ->expects($this->once())
            ->method('isGranted')
            ->with($this->equalTo('IS_AUTHENTICATED_FULLY'))
            ->will($this->returnValue(true))
        ;

        $response = $controller->prelogoutAction($request);

        $this->assertEquals('http://www.google.com/?wreply='.urlencode('/'), $response->headers->get('Location'));
    }

    public function testPrelogoutAuthenticateDev()
    {
        $controller = $this->createController('dev');
        $request = new Request();

        $url = '/logout';

        $this
            ->router
            ->expects($this->once())
            ->method('generate')
            ->with($this->equalTo('universibo_shibboleth_logout'))
            ->will($this->returnValue($url))
        ;

        $this
            ->securityContext
            ->expects($this->once())
            ->method('isGranted')
            ->with($this->equalTo('IS_AUTHENTICATED_FULLY'))
            ->will($this->returnValue(true))
        ;

        $response = $controller->prelogoutAction($request);

        $this->assertEquals($url, $response->headers->get('Location'));
    }

    public function testLoginAnonymous()
    {
        $request = new Request();

        $expectedRequest = clone $request;
        $expectedRequest->attributes->set('_controller', 'FOSUserBundle:Security:login');

        $this
            ->kernel
            ->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($expectedRequest), $this->equalTo(HttpKernelInterface::SUB_REQUEST))
            ->will($this->returnValue(new Response($content = 'Hello World')))
        ;

        $response = $this
            ->createController()
            ->loginAction($request)
        ;

        $this->assertEquals($content, $response->getContent());
    }
}
