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
                ->scalarNode('tmp_dir')
                    ->defaultValue('/tmp/CanalTP/MediaManager/')
                ->end()
                ->scalarNode('company_path')
                    ->defaultValue(__DIR__ . '/../Resources/config/company.yml')
                ->end()
                ->scalarNode('navitia_path')
                    ->defaultValue(__DIR__ . '/../Resources/config/navitia.yml')
                ->end()
            ->end()
        ->end()
        ;

        return $treeBuilder;
    }
}
