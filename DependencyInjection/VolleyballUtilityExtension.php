<?php
namespace Volleyball\Bundle\UtilityBundle\DependencyInjection;

use \Symfony\Component\Config\Definition\Processor;
use \Symfony\Component\Config\FileLocator;
use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\DependencyInjection\Loader;
use \Symfony\Component\HttpKernel\DependencyInjection\Extension;

use \Volleyball\Bundle\UtilityBundle\DependencyInjection\Driver\DatabaseDriverFactory;

class VolleyballUtilityExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $processor = new Processor();
        $config    = $processor->processConfiguration(new Configuration(), $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('twig.yml');

        $classes = isset($config['resources']) ? $config['resources'] : array();

        $container->setParameter('volleyball.utility.settings', $config['settings']);

        $this->createUtilityServices($classes, $container);

        if ($container->hasParameter('volleyball.config.classes')) {
            $classes = array_merge($classes, $container->getParameter('volleyball.config.classes'));
        }

        $container->setParameter('volleyball.config.classes', $classes);
    }

    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    private function createUtilityServices(array $configs, ContainerBuilder $container)
    {
        foreach ($configs as $name => $config) {
            list($prefix, $utilityName) = explode('.', $name);

            DatabaseDriverFactory::get(
                $config['driver'],
                $container,
                $prefix,
                $utilityName,
                array_key_exists('templates', $config) ? $config['templates'] : null
            )->load($config['classes']);
        }
    }
}
