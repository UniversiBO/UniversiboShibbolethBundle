<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ShibbolethToken extends AbstractToken
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Security\Core\Authentication\Token.TokenInterface::getCredentials()
     */
    public function getCredentials()
    {
        return '';
    }
}
