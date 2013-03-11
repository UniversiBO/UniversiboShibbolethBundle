<?php

namespace Universibo\Bundle\ShibbolethBundle\Tests\Security\Authentication\Firewall;

use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Universibo\Bundle\ShibbolethBundle\Security\Authentication\Token\ShibbolethToken;
use Universibo\Bundle\ShibbolethBundle\Security\Firewall\ShibbolethListener;

class ShibbolethListenerTest extends PHPUnit_Framework_TestCase
{
    private $securityContext;
    private $authenticationManager;
    private $eventDispatcher;
    private $logger;
    private $kernel;
    private $listener;

    protected function setUp()
    {
        $claims = array('username', 'email');
        $this->securityContext = $this->getMock('Symfony\\Component\\Security\\Core\\SecurityContextInterface');
        $this->authenticationManager = $this->getMock('Symfony\\Component\\Security\\Core\\Authentication\\AuthenticationManagerInterface');
        $this->eventDispatcher = $this->getMock('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface');
        $this->logger = $this->getMock('Symfony\\Component\\HttpKernel\\Log\\LoggerInterface');
        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');

        $this->listener = new ShibbolethListener($this->securityContext,
                $this->authenticationManager, $this->eventDispatcher,
                $this->logger, $claims);
    }

    public function testNoClaims()
    {
        $event = new GetResponseEvent($this->kernel, new Request(), HttpKernelInterface::MASTER_REQUEST);
        $this->assertNull($this->listener->handle($event));
    }

    public function testSuccessfulAuth()
    {
        $request = $this->buildRequest();

        $request->server->set('Shib-Session-ID', 1234);
        $request->server->set('email', 'test@example.org');
        $request->server->set('username', 'test');

        $token = new ShibbolethToken();
        $token->setClaims(array(
            'email'    => 'test@example.org',
            'username' => 'test'
        ));

        $cloned = clone $token;
        $cloned->setAuthenticated(true);

        $this
            ->authenticationManager
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->equalto($token))
            ->will($this->returnValue($cloned))
        ;

        $this
            ->securityContext
            ->expects($this->once())
            ->method('setToken')
            ->with($this->equalTo($cloned))
        ;

        $event = new GetResponseEvent($this->kernel, $request, HttpKernelInterface::MASTER_REQUEST);
        $this->listener->handle($event);
    }

    public function testAuthFailed()
    {
        $request = $this->buildRequest();
        $request->server->set('Shib-Session-ID', 1234);

        $this
            ->authenticationManager
            ->expects($this->once())
            ->method('authenticate')
            ->will($this->throwException(new AuthenticationException('message', 3, null)));
        ;

        $event = new GetResponseEvent($this->kernel, $request, HttpKernelInterface::MASTER_REQUEST);
        $this
            ->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo('universibo_shibboleth.auth_failed'))
            ->will($this->returnArgument(1))
        ;

        $this->listener->handle($event);

        $this->assertTrue($event->hasResponse(), 'Event should have a response');
        $response = $event->getResponse();

        $this->assertEquals(403, $response->getStatusCode(), 'Status code 403');
    }

    public function testAuthFailedResponse()
    {
        $request = $this->buildRequest();
        $request->server->set('Shib-Session-ID', 1234);

        $this
            ->authenticationManager
            ->expects($this->once())
            ->method('authenticate')
            ->will($this->throwException(new AuthenticationException('message', 3, null)));
        ;

        $event = new GetResponseEvent($this->kernel, $request, HttpKernelInterface::MASTER_REQUEST);
        $event2 = clone $event;

        $response = new Response('Hello!');
        $event2->setResponse($response);

        $this
            ->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo('universibo_shibboleth.auth_failed'))
            ->will($this->returnValue($event2))
        ;

        $this->listener->handle($event);

        $this->assertTrue($event->hasResponse(), 'Event should have a response');
        $this->assertSame($response, $event->getResponse());
    }

    /**
     * @return Request
     */
    private function buildRequest()
    {
        $request = new Request();
        $session = $this->getMock('Symfony\\Component\\HttpFoundation\\Session\\SessionInterface');
        $request->setSession($session);

        return $request;
    }
}
