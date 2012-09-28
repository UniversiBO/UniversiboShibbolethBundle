<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Factory;

use Symfony\Component\DependencyInjection\DefinitionDecorator;

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
        $providerId = 'security.authentication.provider.shibboleth.'.$id;
        $container
        ->setDefinition($providerId, new DefinitionDecorator('universibo_shibboleth.security.authentication.provider'))
        ;

        $listenerId = 'security.authentication.listener.shibboleth.'.$id;
        $listener = $container->setDefinition($listenerId, new DefinitionDecorator('universibo_shibboleth.security.authentication.listener'));

        return array($providerId, $listenerId, $defaultEntryPoint);
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
    }
}
