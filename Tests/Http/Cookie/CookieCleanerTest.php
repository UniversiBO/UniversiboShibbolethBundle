<?php
namespace Universibo\Bundle\ShibbolethBundle\Tests\Http\Cookie;

use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Universibo\Bundle\ShibbolethBundle\Http\Cookie\CookieCleaner;

class CookieCleanerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Cookie cleaner under test
     *
     * @var CookieCleaner
     */
    private $cleaner;

    protected function setUp()
    {
        $this->cleaner = new CookieCleaner();
    }

    public function testClean()
    {
        $request = new Request();
        $request->cookies->set($cookieName = 'shibsession_hello', 42);

        $response = new Response();
        $this->cleaner->clean($request, $response);

        $cookies = $response->headers->getCookies();
        $this->assertCount(1, $cookies, 'Should return exactly one cooke');

        list($cookie) = $cookies;
        $this->assertEquals($cookieName, $cookie->getName(), 'Cookie name');
        $this->assertNull($cookie->getValue(), 'Cookie value');
    }
}
