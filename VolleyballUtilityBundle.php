<?php
namespace Volleyball\Bundle\UtilityBundle;

use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use \Sylius\Bundle\ResourceBundle\SyliusResourceBundle;

use \Volleyball\Bundle\UtilityBundle\DependencyInjection\Compiler\ObjectToIdentifierServicePass;

class VolleyballUtilityBundle extends AbstractResourceBundle
{
    public static function getSupportedDrivers()
    {
        return array(
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function getBundlePrefix()
    {
        return 'volleyball_utility';
    }
}
