<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\User;

class ShibbolethUserProviderInterface
{
    /**
     * @param array $claims
     */
    public function loadUserByClaims(array $claims);
}
