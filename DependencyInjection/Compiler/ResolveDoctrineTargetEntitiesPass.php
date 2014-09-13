<?php
namespace Volleyball\Bundle\ResourceBundle\DependencyInjection\Compiler;

use \Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use \Symfony\Component\DependencyInjection\ContainerBuilder;

use \Volleyball\Bundle\ResourceBundle\DependencyInjection\DoctrineTargetEntitiesResolver;
use \Volleyball\Bundle\ResourceBundle\VolleyballResourceBundle;

class ResolveDoctrineTargetEntitiesPass implements CompilerPassInterface
{
    /**
     * @var array $interfaces
     */
    private $interfaces;

    /**
     * @var string $bundlePrefix
     */
    private $bundlePrefix;

    public function __construct($bundlePrefix, array $interfaces)
    {
        $this->bundlePrefix = $bundlePrefix;
        $this->interfaces = $interfaces;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (VolleyballResourceBundle::DRIVER_DOCTRINE_ORM === $this->getDriver($container)) {
            $resolver = new DoctrineTargetEntitiesResolver();
            $resolver->resolve($container, $this->interfaces);
        }
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return string
     */
    private function getDriver(ContainerBuilder $container)
    {
        return $container->getParameter(sprintf('%s.driver', $this->bundlePrefix));
    }
}
