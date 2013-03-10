<?php

namespace Universibo\Bundle\ShibbolethBundle\Tests\Security\Authentication\Token;

use PHPUnit_Framework_TestCase;
use Universibo\Bundle\ShibbolethBundle\Security\Factory\ShibbolethFactory;

class ShibbolethFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ShibbolethFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new ShibbolethFactory();
    }

    public function testKeyEqualsShibboleth()
    {
        $this->assertEquals('shibboleth', $this->factory->getKey());
    }

    public function testPositionIsPreAuth()
    {
        $this->assertEquals('pre_auth', $this->factory->getPosition());
    }

    public function testCreate()
    {
        $container = $this->getMock('Symfony\\Component\\DependencyInjection\\ContainerBuilder');

        $container
           ->expects($this->exactly(2))
           ->method('setDefinition')
        ;

        $result = $this->factory->create($container, 'id', null, null, 'entrypoint');

        $this->assertContains('entrypoint', $result);
        $this->assertContains('security.authentication.provider.shibboleth.id', $result);
        $this->assertContains('security.authentication.listener.shibboleth.id', $result);
    }
}
