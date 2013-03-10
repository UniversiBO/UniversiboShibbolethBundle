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
}
