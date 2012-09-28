<?php
namespace Universibo\Bundle\ShibbolethBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

use Universibo\Bundle\ShibbolethBundle\Security\Authentication\Token\ShibbolethToken;

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
     * @var array
     */
    private $claims;

    /**
     * @param SecurityContextInterface       $securityContext
     * @param AuthenticationManagerInterface $authenticationManager
     * @param array                          $claims
     */
    public function __construct(SecurityContextInterface $securityContext,
            AuthenticationManagerInterface $authenticationManager, array $claims)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->claims = $claims;
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

        $claimData = array();
        foreach ($this->claims as $claim) {
            $claimData[$claim] = $request->server->get($claim);
        }

        $token = new ShibbolethToken();
        $token->setClaims($claimData);

        try {
            $authToken = $this->authenticationManager->authenticate($token);

            $this->securityContext->setToken($authToken);
        } catch (AuthenticationException $failed) {
            $response = new Response();
            $response->setStatusCode(403);
            $event->setResponse($response);
        }
    }
}
