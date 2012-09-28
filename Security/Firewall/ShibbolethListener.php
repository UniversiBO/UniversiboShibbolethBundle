<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Firewall;

use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;

use Symfony\Component\Security\Core\SecurityContextInterface;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

use Symfony\Component\Security\Http\Firewall\ListenerInterface;

/**
 * Shibboleth Listener
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ShibbolethListener implements ListenerInterface
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;
    
    /**
     * @var AuthenticationManagerInterface
     */
    private $authenticationManager;
    
    /**
     * @param SecurityContextInterface $securityContext
     * @param AuthenticationManagerInterface $authenticationManager
     * @param array $claims
     */
    public function __construct(SecurityContextInterface $securityContext,
            AuthenticationManagerInterface $authenticationManager)
    {
    	$this->securityContext = $securityContext;
    	$this->authenticationManager = $authenticationManager;
    }
    
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Security\Http\Firewall.ListenerInterface::handle()
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        // checking if this page is secured by Shibboleth
        if (is_null($sessionId = $request->server->get('Shib-Session-ID'))) {
            return;
        }
    }
}
