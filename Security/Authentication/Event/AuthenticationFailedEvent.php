<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Authentication\Event;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Universibo\Bundle\ShibbolethBundle\Security\Authentication\Event\AuthenticationFailedEvent;

/*
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class AuthenticationFailedEvent extends GetResponseEvent
{
    /**
     * @var array
     */
    private $claims;

    /**
     * @var AuthenticationException
     */
    private $exception;

    public function setClaims(array $claims)
    {
        $this->claims = $claims;

        return $this;
    }

    public function getClaims()
    {
        return $this->claims;
    }

    /**
     * Sets the exception
     *
     * @param  AuthenticationException   $exception
     * @return AuthenticationFailedEvent
     */
    public function setException(AuthenticationException $exception)
    {
        $this->exception = $exception;

        return $this;
    }

    /**
     * @return AuthenticationException
     */
    public function getException()
    {
        return $this->exception;
    }
}
