<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * Shibboleth Token
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ShibbolethToken extends AbstractToken
{
    /**
     * @var array
     */
    private $claims;

    /**
     * @param  array                                                                             $claims
     * @return \Universibo\Bundle\ShibbolethBundle\Security\Authentication\Token\ShibbolethToken
     */
    public function setClaims(array $claims)
    {
        $this->claims = $claims;

        return $this;
    }

    /**
     * @return array
     */
    public function getClaims()
    {
        return $this->claims;
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Security\Core\Authentication\Token.TokenInterface::getCredentials()
     */
    public function getCredentials()
    {
        return '';
    }
}
