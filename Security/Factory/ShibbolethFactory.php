<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Factory;
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
    public function getPosition()
    {
        // TODO: Auto-generated method stub

    }
    public function getKey()
    {
        // TODO: Auto-generated method stub

    }
    public function addConfiguration(NodeDefinition $builder)
    {
        // TODO: Auto-generated method stub

    }

}
