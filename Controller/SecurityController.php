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
        if ('prod' === $this->get('kernel')->getEnvironment()) {
            $redirectUri = $this->container->getParameter('universibo_shibboleth.idp_url.logout');
        } else {
            $redirectUri = $this->generateUrl('universibo_shibboleth_logout');
        }

        return new RedirectResponse($redirectUri);
    }
}
