<?php

namespace Universibo\Bundle\ShibbolethBundle\Security\Authentication\Provider;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ShibbolethProviderFactory
{
    /**
     * @param  ContainerInterface                                                                      $container
     * @param  string                                                                                  $name
     * @return \Universibo\Bundle\ShibbolethBundle\Security\Authentication\Provider\ShibbolethProvider
     */
    public static function get(ContainerInterface $container, $name)
    {
        return new ShibbolethProvider($container->get($name));
    }
}
