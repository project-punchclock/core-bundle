<?php
namespace Volleyball\Bundle\ResourceBundle\DependencyInjection\Driver;

use \Symfony\Component\DependencyInjection\ContainerBuilder;

use \Volleyball\Bundle\ResourceBundle\VolleyballResourceBundle;
use \Volleyball\Component\Resource\Exception\Driver\UnknownDriverException;

class DatabaseDriverFactory
{
    public static function get(
        $type = VolleyballResourceBundle::DRIVER_DOCTRINE_ORM,
        ContainerBuilder $container,
        $prefix,
        $resourceName,
        $templates = null
    ) {
        switch ($type) {
            case VolleyballUtilityBundle::DRIVER_DOCTRINE_ORM:
                return new DoctrineORMDriver($container, $prefix, $resourceName, $templates);
            case VolleyballUtilityBundle::DRIVER_DOCTRINE_MONGODB_ODM:
                return new DoctrineODMDriver($container, $prefix, $resourceName, $templates);
            default:
                throw new UnknownDriverException($type);
        }
    }
}
