<?php
namespace Volleyball\Bundle\UtilityBundle\DependencyInjection;

use \Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineTargetEntitiesResolver
{
    /**
     * {@inheritdoc}
     */
    public function resolve(ContainerBuilder $container, array $interfaces)
    {
        if (!$container->hasDefinition('doctrine.orm.listeners.resolve_target_entity')) {
            throw new \RuntimeException('Cannot find Doctrine RTEL');
        }

        $resolveTargetEntityListener = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');

        foreach ($interfaces as $interface => $model) {
            $resolveTargetEntityListener
                ->addMethodCall('addResolveTargetEntity', array(
                    $this->getInterface($container, $interface),
                    $this->getClass($container, $model),
                    array()
                ))
            ;
        }

        if (!$resolveTargetEntityListener->hasTag('doctrine.event_listener')) {
            $resolveTargetEntityListener->addTag('doctrine.event_listener', array('event' => 'loadClassMetadata'));
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $key
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    private function getInterface(ContainerBuilder $container, $key)
    {
        if ($container->hasParameter($key)) {
            return $container->getParameter($key);
        }

        if (interface_exists($key)) {
            return $key;
        }

        throw new \InvalidArgumentException(
            sprintf('The interface %s does not exists.', $key)
        );
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $key
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    private function getClass(ContainerBuilder $container, $key)
    {
        if ($container->hasParameter($key)) {
            return $container->getParameter($key);
        }

        if (class_exists($key)) {
            return $key;
        }

        throw new \InvalidArgumentException(
            sprintf('The class %s does not exists.', $key)
        );
    }
}
