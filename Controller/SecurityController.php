<?php

namespace Universibo\Bundle\ShibbolethBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class SecurityController extends Controller
{
    public function loginAction()
    {
        $wreply = $this->getRequest()->query->get('wreply', '/');

        return $this->redirect($wreply);
    }

    public function logoutAction()
    {
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

            $redirectUri.= '?wreply=' . $this->getWreply();
        } else {
            $redirectUri = $this->generateUrl('universibo_shibboleth_logout');
        }

        return new RedirectResponse($redirectUri);
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
