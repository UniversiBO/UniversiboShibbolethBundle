<?php

namespace Universibo\Bundle\ShibbolethBundle\Tests\DependencyInjection;

/**
 * Some methods are from FOSUserBundle (Copyright (c) 2010-2011 FriendsOfSymfony)
 * @author Davide Bellettini <davide.bellettini>
 */
use Symfony\Component\Yaml\Parser;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Universibo\Bundle\ShibbolethBundle\DependencyInjection\UniversiboShibbolethExtension;

class UniversiboShibbolethExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testMissingBaseUrlThrowsException()
    {
    	$loader = new UniversiboShibbolethExtension();
    	$config = $this->getConfig();
    	unset($config['idp_url']['base']);
    	$loader->load(array($config), new ContainerBuilder());
    }
    
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testMissingInfoUrlThrowsException()
    {
    	$loader = new UniversiboShibbolethExtension();
    	$config = $this->getConfig();
    	unset($config['idp_url']['base']);
    	$loader->load(array($config), new ContainerBuilder());
    }
    
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testMissingLogoutUrlThrowsException()
    {
    	$loader = new UniversiboShibbolethExtension();
    	$config = $this->getConfig();
    	unset($config['idp_url']['logout']);
    	$loader->load(array($config), new ContainerBuilder());
    }
    
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testMissingClaimsThrowsException()
    {
    	$loader = new UniversiboShibbolethExtension();
    	$config = $this->getConfig();
    	unset($config['claims']);
    	$loader->load(array($config), new ContainerBuilder());
    }
    
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testMissingAfterLogoutRouteThrowsException()
    {
    	$loader = new UniversiboShibbolethExtension();
    	$config = $this->getConfig();
    	unset($config['route']['after_logout']);
    	$loader->load(array($config), new ContainerBuilder());
    }
    
    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testMissingUserProviderThrowsException()
    {
    	$loader = new UniversiboShibbolethExtension();
    	$config = $this->getConfig();
    	unset($config['user_provider']);
    	$loader->load(array($config), new ContainerBuilder());
    }
    
    public function testCorrectConfig()
    {
    	$loader = new UniversiboShibbolethExtension();
    	$config = $this->getConfig();
    	$loader->load(array($config), new ContainerBuilder());
    }

    private function getConfig()
    {
        $yaml = <<<EOF
idp_url:
  base: 'https://idp.example.com/adfs/ls/'
  info: 'infoSSO.aspx'
  logout: 'prelogout.aspx'
route:
  after_logout: 'homepage'
claims:
  - eppn
  - givenName
  - isMemberOf
  - sn
user_provider: universibo_website.user.provider
EOF;
        $parser = new Parser();
        
        return $parser->parse($yaml);
    }
}