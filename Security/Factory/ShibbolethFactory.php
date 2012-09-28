<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Factory;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

/**
 * Shibboleth Security Factory
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ShibbolethFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config,
            $userProvider, $defaultEntryPoint)
    {
        // TODO: Auto-generated method stub

    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory.SecurityFactoryInterface::getPosition()
     */
    public function getPosition()
    {
        return 'pre_auth';
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory.SecurityFactoryInterface::getKey()
     */
    public function getKey()
    {
        return 'shibboleth';
    }

    public function addConfiguration(NodeDefinition $builder)
    {
        // TODO: Auto-generated method stub

    }

}
