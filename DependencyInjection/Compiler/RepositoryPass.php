<?php
namespace Volleyball\Bundle\UtilityBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RepositoryPass implements CompilerPassInterface
{
    /**
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$factory = $container->findDefinition('volleyball.repository.factory')) {
            return;
        }
 
        $repositories = array();
        foreach ($container->findTaggedServiceIds('volleyball.repository') as $id => $params) {
            foreach ($params as $param) {
                if ('factory' == $param['alias']) {
                    continue;
                }
                
                $repositories[$param['class']] = $id;
                $repository = $container->findDefinition($id);
                $repository->replaceArgument(0, new Reference('doctrine.orm.default_entity_manager'));
 
                $definition = new Definition();
                $definition->setClass('Doctrine\ORM\Mapping\ClassMetadata');
                $definition->setFactoryService('doctrine.orm.default_entity_manager');
                $definition->setFactoryMethod('getClassMetadata');
                $definition->setArguments([$param['class']]);
                $repository->replaceArgument(1, $definition);
            }
        }
        $factory->replaceArgument(0, $repositories);
 
        $container->findDefinition('doctrine.orm.configuration')->addMethodCall('setRepositoryFactory', [$factory]);
    }
}
