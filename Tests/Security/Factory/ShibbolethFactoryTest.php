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
}
