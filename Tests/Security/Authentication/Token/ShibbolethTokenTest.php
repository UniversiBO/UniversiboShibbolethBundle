<?php 

namespace Universibo\Bundle\ShibbolethBundle\Tests\Security\Authentication\Token;

use Universibo\Bundle\ShibbolethBundle\Security\Authentication\Token\ShibbolethToken;

class ShibbolethTokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ShibbolethToken
     */
    private $token;
    
    protected function setUp()
    {
        $this->token = new ShibbolethToken();
    }
    
    public function testClaimAccessors()
    {
        $claims = array ('eppn' => 'test.mail@provider.com');
        
        $this->token->setClaims($claims);
        
        $this->assertEquals($claims, $this->token->getClaims());
    }
}