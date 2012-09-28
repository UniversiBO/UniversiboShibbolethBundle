<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\User;

interface ShibbolethUserProviderInterface
{
    /**
     * @param array $claims
     */
    public function loadUserByClaims(array $claims);
}
