<?php
namespace Volleyball\Bundle\UtilityBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Volleyball\Bundle\UtilityBundle\DependencyInjection\Compiler\RepositoryPass;

class VolleyballUtilityBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        
        $container->addCompilerPass(new RepositoryPass());
    }
}
