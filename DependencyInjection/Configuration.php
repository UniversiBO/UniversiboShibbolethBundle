<?php

namespace Universibo\Bundle\ShibbolethBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('universibo_shibboleth');

        $rootNode
            ->children()
                ->arrayNode('idp_url')
                    ->children()
                        ->scalarNode('base')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('info')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('logout')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('route')
                    ->children()
                        ->scalarNode('after_logout')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('claims')
                    ->isRequired()
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
