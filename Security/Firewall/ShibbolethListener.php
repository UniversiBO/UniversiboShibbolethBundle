<?php
namespace Universibo\Bundle\ShibbolethBundle\Security\Firewall;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Universibo\Bundle\ShibbolethBundle\Security\Authentication\Event\AuthenticationFailedEvent;
use Universibo\Bundle\ShibbolethBundle\Security\Authentication\Token\ShibbolethToken;

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
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var array
     */
    private $claims;

    /**
     *
     * @param SecurityContextInterface       $securityContext
     * @param AuthenticationManagerInterface $authenticationManager
     * @param EventDispatcherInterface       $eventDispatcher
     * @param array                          $claims
     */
    public function __construct(SecurityContextInterface $securityContext,
            AuthenticationManagerInterface $authenticationManager,
            EventDispatcherInterface $eventDispatcher, array $claims)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->eventDispatcher = $eventDispatcher;
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
        if (is_null($sessionId = $this->getClaim($request, 'Shib-Session-ID'))) {
            return;
        }

        $claimData = array();
        foreach ($this->claims as $claim) {
            $claimData[$claim] = $this->getClaim($request, $claim);
        }

        $token = new ShibbolethToken();
        $token->setClaims($claimData);

        try {
            $authToken = $this->authenticationManager->authenticate($token);

            $this->securityContext->setToken($authToken);
            $request->getSession()->set('shibbolethClaims', $claimData);
        } catch (AuthenticationException $failed) {

            $newEvent = new AuthenticationFailedEvent($event->getKernel(),
                    $event->getRequest(), $event->getRequestType());
            $newEvent->setClaims($claimData);
            $newEvent->setException($failed);

            $dispatchedEvent = $this
                ->eventDispatcher
                ->dispatch('universibo_shibboleth.auth_failed', $newEvent)
            ;

            if ($dispatchedEvent->hasResponse()) {
                $response = $dispatchedEvent->getResponse();
            } else {
                $response = new Response();
                $response->setStatusCode(403);
                $response->setContent('403 forbidden, please send us an email');
            }

            $event->setResponse($response);
        }
    }

    private function getClaim(Request $request, $claimName)
    {
        $claim = $request->server->get($claimName);

        return $claim === null ? $request->server->get('REDIRECT_'.$claimName) : $claim;
    }
}
