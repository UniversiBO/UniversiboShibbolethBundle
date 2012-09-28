<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Authentication\Provider;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ShibbolethProvider implements AuthenticationProviderInterface
{
    public function supports(TokenInterface $token)
    {
        // TODO: Auto-generated method stub

    }

}
