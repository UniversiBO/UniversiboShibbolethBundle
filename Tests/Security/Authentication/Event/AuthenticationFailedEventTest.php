<?php
/**
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Universibo\Bundle\ShibbolethBundle\Tests\Security\Authentication\Event;

use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Universibo\Bundle\ShibbolethBundle\Security\Authentication\Event\AuthenticationFailedEvent;

class AuthenticationFailedEventTest extends PHPUnit_Framework_TestCase
{
    /**
     * Event
     * @var AuthenticationFailedEvent
     */
    private $event;

    protected function setUp()
    {
        $this->kernel = $this->getMock('Symfony\\Component\\HttpKernel\\HttpKernelInterface');
        $this->event = new AuthenticationFailedEvent($this->kernel, new Request(), HttpKernelInterface::MASTER_REQUEST);
    }

    public function testClaimsAccessors()
    {
        $claims = array ('hello', 'world');

        $this->assertSame($this->event, $this->event->setClaims($claims));
        $this->assertEquals($claims, $this->event->getClaims());
    }

    public function testExceptionAccessors()
    {
        $exception = new AuthenticationException('message', 1, null);

        $this->assertSame($this->event, $this->event->setException($exception));
        $this->assertEquals($exception, $this->event->getException());
    }
}
