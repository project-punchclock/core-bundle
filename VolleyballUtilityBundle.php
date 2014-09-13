<?php
namespace Volleyball\Bundle\UtilityBundle;

use \Symfony\Component\HttpKernel\Bundle\Bundle;
use \Symfony\Component\DependencyInjection\ContainerBuilder;

use \Volleyball\Bundle\UtilityBundle\DependencyInjection\Compiler\ObjectToIdentifierServicePass;

class VolleyballUtilityBundle extends Bundle
{
    const DRIVER_DOCTRINE_ORM         = 'doctrine/orm';
    const DRIVER_DOCTRINE_MONGODB_ODM = 'doctrine/mongodb-odm';
    
    public function build(ContainerBuilder $container)
    {
//        $container->addCompilerPass(new ObjectToIdentifierServicePass());
    }
}
