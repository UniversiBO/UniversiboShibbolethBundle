<?php

namespace Universibo\Bundle\ShibbolethBundle\Tests\Security\Authentication\Token;

use PHPUnit_Framework_TestCase;
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
}
