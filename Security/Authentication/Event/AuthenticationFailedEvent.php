<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Authentication\Event;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/*
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class AuthenticationFailedEvent extends GetResponseEvent
{
    private $claims;

    public function setClaims(array $claims)
    {
        $this->claims = $claims;

        return $this;
    }

    public function getClaims()
    {
        return $this->claims;
    }
}
