<?php

namespace Universibo\Bundle\ShibbolethBundle\Tests\DependencyInjection;

/**
 * Some methods are from FOSUserBundle (Copyright (c) 2010-2011 FriendsOfSymfony)
 * @author Davide Bellettini <davide.bellettini>
 */
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
    }
    
    private function assertParameter($value, $key)
    {
    	$this->assertEquals($value, $this->configuration->getParameter($key), sprintf('%s parameter is correct', $key));
    }
}