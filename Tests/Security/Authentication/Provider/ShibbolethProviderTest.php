<?php

namespace Universibo\Bundle\ShibbolethBundle\Tests\Security\Authentication\Token;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Universibo\Bundle\ShibbolethBundle\Security\Authentication\Provider\ShibbolethProvider;
use Universibo\Bundle\ShibbolethBundle\Security\Authentication\Token\ShibbolethToken;
use Universibo\Bundle\ShibbolethBundle\Security\User\ShibbolethUserProviderInterface;

class ShibbolethProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ShibbolethProvider
     */
    private $provider;

    /**
     * User provider mock
     * @var ShibbolethUserProviderInterface
     */
    private $userProvider;

    protected function setUp()
    {
        $this->userProvider = $this->getMock('Universibo\\Bundle\\ShibbolethBundle\\Security\\User\\ShibbolethUserProviderInterface');
        $this->provider = new ShibbolethProvider($this->userProvider);
    }

    public function testSupportsShibbolethToken()
    {
        $this->assertTrue($this->provider->supports(new ShibbolethToken()));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnsupportedTokenTypeThrowsException()
    {
        $this->provider->authenticate(new UsernamePasswordToken('user', 'pass', 'key'));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AuthenticationException
     */
    public function testNoUserThrowsException()
    {
        $claims = array('email' => 'hello@example.org');
        $token = new ShibbolethToken();
        $token->setClaims($claims);

        $this
            ->userProvider
            ->expects($this->once())
            ->method('loadUserByClaims')
            ->with($this->equalTo($claims))
            ->will($this->returnValue(null))
        ;

        $this->provider->authenticate($token);
    }

    public function testFoundUser()
    {
        $claims = array('email' => 'hello@example.org');
        $token = new ShibbolethToken();
        $token->setClaims($claims);

        $user = $this->getMock('Symfony\\Component\\Security\\Core\\User\\UserInterface');
        $user
            ->expects($this->once())
            ->method('getRoles')
            ->will($this->returnValue(array('ROLE_USER')))
        ;

        $this
            ->userProvider
            ->expects($this->once())
            ->method('loadUserByClaims')
            ->with($this->equalTo($claims))
            ->will($this->returnValue($user))
        ;

        $authenticatedToken = $this->provider->authenticate($token);

        $this->assertSame($user, $authenticatedToken->getUser());
        $this->assertTrue($authenticatedToken->isAuthenticated());
        $this->assertEquals($claims, $authenticatedToken->getClaims());
        $this->assertCount(1, $roles = $authenticatedToken->getRoles());
        $this->assertEquals('ROLE_USER', $roles[0]->getRole());
    }
}
