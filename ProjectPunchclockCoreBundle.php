<?php
namespace ProjectPunchclock\Bundle\CoreBundle;

use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Sylius\Bundle\ResourceBundle\AbstractResourceBundle;
use \Sylius\Bundle\ResourceBundle\SyliusResourceBundle;

use \ProjectPunchclock\Bundle\CoreBundle\DependencyInjection\Compiler\ObjectToIdentifierServicePass;

class ProjectPunchclockCoreBundle extends AbstractResourceBundle
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
        return 'project_punchclock_core';
    }
}
