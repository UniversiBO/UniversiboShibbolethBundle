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
        $request = $this->getRequest();
        
        $url = $request->headers->get('Referer');
        $this->redirect($url);
        
        // TODO method stub
    }
    
    public function logoutAction()
    {
    }
}
