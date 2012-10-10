<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Http\Logout;
use Symfony\Component\HttpFoundation\Cookie;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ShibbolethLogoutHandler implements LogoutHandlerInterface
{
    /**
     * @var string
     */
    private $logoutUri;
    
    /**
     * @param string $logoutUri
     */
    public function __construct($logoutUri)
    {
        $this->logoutUri = $logoutUri;
    }
    
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Security\Http\Logout.LogoutHandlerInterface::logout()
     */
    public function logout(Request $request, Response $response,
            TokenInterface $token)
    {
        if ($request->query->get('shibboleth')) {
            foreach ($request->cookies->keys() as $key) {
            	if (preg_match('/shibsession/', $key)) {
            		$response->headers->setCookie(new Cookie($key));
            	}
            }
            
            $request->getSession()->invalidate();
            
            $greenCheck = '/bundles/universiboshibboleth/images/greencheck.gif';
            $location = $request->getUriForPath($greenCheck);
        } else {
            $location = $this->logoutUri;
        }
        
        $response->headers->set('Location', $location);
        $response->setStatusCode(302);
    }
}
