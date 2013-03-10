<?php

namespace Universibo\Bundle\ShibbolethBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Universibo\Bundle\ShibbolethBundle\Security\Http\Logout\ShibbolethLogoutHandler;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class SecurityController
{
    /**
     * Environment
     *
     * @var string
     */
    private $environment;

    /**
     * Security context
     *
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * Router
     *
     * @var RouterInterface
     */
    private $router;

    /**
     * Firewall name
     *
     * @var string
     */
    private $firewallName;

    /**
     * Redirect after login route
     * @var string
     */
    private $afterLoginRoute;

    /**
     * Logout url
     * @var string
     */
    private $idpLogoutUrl;

    /**
     * Class constructor
     *
     * @param KernelInterface          $kernel
     * @param SecurityContextInterface $securityContext
     * @param string                   $firewallName
     * @param string                   $afterLoginRoute
     * @param string                   $idpLogoutUrl
     */
    public function __construct(KernelInterface $kernel, SecurityContextInterface $securityContext,
            RouterInterface $router, $firewallName, $afterLoginRoute, $idpLogoutUrl)
    {
        $this->environment     = $kernel->getEnvironment();
        $this->securityContext = $securityContext;
        $this->router          = $router;
        $this->firewallName    = $firewallName;
        $this->afterLoginRoute = $afterLoginRoute;
        $this->idpLogoutUrl    = $idpLogoutUrl;
    }

    public function loginAction(Request $request)
    {
        if (!$this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->forward('FOSUserBundle:Security:login');
        }

        $defaultTarget = $this->generateUrl($this->afterLoginRoute, array(), true);
        $firewallName = $this->firewallName;
        $target = $request->getSession()->get('_security.'.$firewallName.'.target_path', $defaultTarget);
        $wreply = $request->query->get('wreply', $target);

        return $this->redirect($wreply);
    }

    public function logoutAction()
    {
    }

    public function shiblogoutAction(Request $request)
    {
        $context = $this->securityContext;

        if (!$context->isGranted('IS_AUTHENTICATED_FULLY')) {
            $logoutHandler = new ShibbolethLogoutHandler($this->router);

            $request->query->set('shibboleth', 'true');
            $response = new Response();
            $logoutHandler->logout($request, $response, $context->getToken());

            return $response;
        }

        return $this->redirect($this->generateUrl('logout', array('shibboleth' => 'true')));
    }

    /**
     * @return RedirectResponse
     */
    public function prelogoutAction(Request $request)
    {
        if (!$this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->getWreply($request));
        }

        if ('prod' === $this->environment) {
            $redirectUri = $this->idpLogoutUrl;
            $redirectUri.= '?wreply=' . urlencode($this->getWreply($request));
        } else {
            $redirectUri = $this->generateUrl('universibo_shibboleth_logout');
        }

        return new RedirectResponse($redirectUri);
    }

    public function greenCheckAction()
    {
        $greenCheckFile = __DIR__.'/../Resources/public/images/greencheck.gif';

        $response = new Response(file_get_contents($greenCheckFile));
        $response->setStatusCode(200);
        $response->setClientTtl(0);
        $response->headers->set('Content-type', 'image/gif');
        $response->setTtl(0);

        return $response;
    }

    private function getWreply(Request $request)
    {
        $wreply = $request->query->get('wreply', $request->server->get('HTTP_REFERER'));

        if (is_null($wreply)) {
            $wreply = $this->generateUrl('homepage');
        }

        return $wreply;
    }

    /**
     * Generates an url
     *
     * @param  string  $name
     * @param  array   $parameters
     * @param  boolean $referenceType
     * @return string
     */
    private function generateUrl($name, $parameters = array(), $referenceType = RouterInterface::ABSOLUTE_PATH)
    {
        return $this->router->generate($name, $parameters, $referenceType);
    }
}
