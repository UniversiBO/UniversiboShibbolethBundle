<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Universibo\Bundle\ShibbolethBundle\Security\Authentication\Token\ShibbolethToken;
use Universibo\Bundle\ShibbolethBundle\Security\User\ShibbolethUserProviderInterface;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ShibbolethProvider implements AuthenticationProviderInterface
{
    /**
     * @var ShibbolethUserProviderInterface
     */
    private $userProvider;

    public function __construct(ShibbolethUserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Security\Core\Authentication\Provider.AuthenticationProviderInterface::supports()
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof ShibbolethToken;
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Security\Core\Authentication.AuthenticationManagerInterface::authenticate()
     */
    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByClaims($token->getClaims());

        // should never reach this
        if (!$user instanceof UserInterface) {
            throw new AuthenticationException('Provider returned no user');
        }

        $authenticatedToken = new ShibbolethToken($user->getRoles());
        $authenticatedToken->setClaims($token->getClaims());
        $authenticatedToken->setUser($user);
        $authenticatedToken->setAuthenticated(true);

        return $authenticatedToken;
    }

}
