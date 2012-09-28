<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Authentication\Provider;
use Universibo\Bundle\ShibbolethBundle\Security\Authentication\Token\ShibbolethToken;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ShibbolethProvider implements AuthenticationProviderInterface
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Security\Core\Authentication\Provider.AuthenticationProviderInterface::supports()
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof ShibbolethToken;
    }
}
