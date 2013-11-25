<?php

namespace CanalTP\MediaManagerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration
 *
 * To learn more see symfony.com -> extension
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('canal_tp_media_manager');

        $rootNode
        ->children()
            ->arrayNode('configurations')
                ->info('MediaManager configuration')
                ->cannotBeEmpty()
                ->children()
                    ->scalarNode('name')
                        ->info('Name of SIM')
                        ->defaultValue('Unknown')
                    ->end()
                    ->arrayNode('storage')
                        ->info('Configation of SIM storage')
                        ->children()
                            ->scalarNode('type')->defaultValue(
                                'filesystem'
                            )->end()
                            ->scalarNode('path')->defaultValue(
                                '/tmp/my_storage/'
                            )->end()
                        ->end()
                    ->end()
                    ->scalarNode('strategy')
                        ->info('Configuration of SIM strategy')
                        ->defaultValue('default')
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
