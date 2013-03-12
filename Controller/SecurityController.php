<?php

namespace Universibo\Bundle\ShibbolethBundle\Controller;

use LogicException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

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
     * Kernel
     *
     * @var HttpKernelInterface
     */
    private $kernel;

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
     * Logout handler
     *
     * @var LogoutHandlerInterface
     */
    private $logoutHandler;

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
     * @param HttpKernelInterface      $kernel
     * @param SecurityContextInterface $securityContext
     * @param RouterInterface          $router
     * @param LogoutHandlerInterface   $logoutHandler
     * @param string                   $firewallName
     * @param string                   $afterLoginRoute
     * @param string                   $idpLogoutUrl
     */
    public function __construct(HttpKernelInterface $kernel, SecurityContextInterface $securityContext,
            RouterInterface $router, LogoutHandlerInterface $logoutHandler, $firewallName, $afterLoginRoute, $idpLogoutUrl)
    {
        $this->environment     = $kernel->getEnvironment();
        $this->kernel          = $kernel;
        $this->securityContext = $securityContext;
        $this->router          = $router;
        $this->logoutHandler   = $logoutHandler;
        $this->firewallName    = $firewallName;
        $this->afterLoginRoute = $afterLoginRoute;
        $this->idpLogoutUrl    = $idpLogoutUrl;
    }

    public function loginAction(Request $request)
    {
        if (!$this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $path['_controller'] = 'FOSUserBundle:Security:login';
            $subRequest = $request->duplicate(array(), null, $path);

            return $this->kernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
        }

        $defaultTarget = $this->generateUrl($this->afterLoginRoute, array(), true);
        $firewallName = $this->firewallName;
        $target = $request->getSession()->get('_security.'.$firewallName.'.target_path', $defaultTarget);
        $wreply = $request->query->get('wreply', $target);

        return $this->redirect($wreply);
    }

    public function logoutAction()
    {
        throw new LogicException('Request should be intercepted');
    }

    public function shiblogoutAction(Request $request)
    {
        $context = $this->securityContext;

        if (!$context->isGranted('IS_AUTHENTICATED_FULLY')) {
            $request->query->set('shibboleth', 'true');
            $response = new Response();
            $this->logoutHandler->logout($request, $response, $context->getToken());

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

        return $this->redirect($redirectUri);
    }

    /**
     * Displays Green Check gif
     *
     * @return Response
     */
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

    /**
     * Creates a RedirectResponse
     *
     * @param  string           $url
     * @return RedirectResponse
     */
    private function redirect($url)
    {
        return new RedirectResponse($url);
    }
}
