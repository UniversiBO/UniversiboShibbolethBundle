<?php
namespace Universibo\Bundle\ShibbolethBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}
