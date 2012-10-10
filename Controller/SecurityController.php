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
        return $this->redirect('/');

        // TODO method stub
    }

    public function logoutAction()
    {
    }
}
