<?php
/**
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Universibo\Bundle\ShibbolethBundle\Http\Cookie;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class CookieCleaner
{
    public function clean(Request $request, Response $response, $pattern = '/shibsession/')
    {
        foreach ($request->cookies->keys() as $key) {
            if (preg_match($pattern, $key)) {
                $response->headers->setCookie(new Cookie($key));
            }
        }
    }
}
