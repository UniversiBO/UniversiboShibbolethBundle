<?php
/**
 * @license MIT
 */
namespace Universibo\Bundle\ShibbolethBundle\Security\Authentication\Provider;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ShibbolethProviderFactory
{
    /**
     * @param  ContainerInterface $container
     * @param  string             $name
     * @return ShibbolethProvider
     */
    public static function get(ContainerInterface $container, $name)
    {
        return new ShibbolethProvider($container->get($name));
    }
}
