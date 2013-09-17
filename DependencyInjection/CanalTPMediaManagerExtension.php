<?php

namespace CanalTP\MediaManagerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Parser;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see symfony.com -> extension
 */
class CanalTPMediaManagerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $yaml = new Parser();
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader(
            $container, new FileLocator(__DIR__.'/../Resources/config')
        );

        $container->setParameter('path.tmp', $config['tmp_dir']);
        $container->setParameter(
            'config.company',
            $yaml->parse(file_get_contents($config['company_path']))
        );
        $container->setParameter(
            'config.navitia',
            $yaml->parse(file_get_contents($config['navitia_path']))
        );

        $loader->load('services.yml');
    }
}
