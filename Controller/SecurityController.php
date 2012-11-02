<?php

namespace Universibo\Bundle\ShibbolethBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Universibo\Bundle\ShibbolethBundle\Security\Http\Logout\ShibbolethLogoutHandler;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class SecurityController extends Controller
{
    public function loginAction()
    {
        $request = $this->getRequest();
        $target = $request->getSession()->get('_security.main.target_path', '/');
        $wreply = $request->query->get('wreply', $target);

        return $this->redirect($wreply);
    }

    public function logoutAction()
    {
    }

    public function shiblogoutAction(Request $request)
    {
        $context = $this->get('security.context');

        if (!$context->isGranted('IS_AUTHENTICATED_FULLY')) {
            $logoutHandler = new ShibbolethLogoutHandler($this->get('router'));

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
    public function prelogoutAction()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->getWreply());
        }

        $env = $this->get('kernel')->getEnvironment();
        if ('prod' === $env) {
            $redirectUri = $this
                    ->container
                    ->getParameter('universibo_shibboleth.idp_url.logout')
            ;

            $redirectUri.= '?wreply=' . urlencode($this->getWreply());
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

    private function getWreply()
    {
        $request = $this->getRequest();
        $wreply = $request->query->get('wreply', $request->server->get('HTTP_REFERER'));

        if (is_null($wreply)) {
            $wreply = $this->generateUrl('homepage');
        }

        return $wreply;
    }

}
