<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Firewall;
use Universibo\Bundle\ShibbolethBundle\Security\Authentication\Token\ShibbolethToken;

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
     * (non-PHPdoc)
     * @see Symfony\Component\Security\Http\Firewall.ListenerInterface::handle()
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        
        // checking if this page is secured by Shibboleth
        if(is_null($sessionId = $request->server->get('Shib-Session-ID'))) {
            return;
        }
    }
}
