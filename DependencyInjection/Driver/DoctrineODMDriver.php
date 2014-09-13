<?php
namespace Volleyball\Bundle\UtilityBundle\DependencyInjection\Driver;

use \Symfony\Component\DependencyInjection\Definition;
use \Symfony\Component\DependencyInjection\Reference;

use \Volleyball\Bundle\ResourceBundle\VolleyballResourceBundle;

class DoctrineODMDriver extends AbstractDatabaseDriver
{
    /**
     * {@inheritdoc}
     */
    public function getSupportedDriver()
    {
        return VolleyballUtilityBundle::DRIVER_DOCTRINE_MONGODB_ODM;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRepositoryDefinition(array $classes)
    {
        $repositoryClass = 'Volleyball\Bundle\ResourceBundle\Doctrine\ODM\MongoDB\DocumentRepository';

        if (isset($classes['repository'])) {
            $repositoryClass  = $classes['repository'];
        }

        $unitOfWorkDefinition = new Definition('Doctrine\\ODM\\MongoDB\\UnitOfWork');
        $unitOfWorkDefinition
            ->setFactoryService('doctrine.odm.mongodb.document_manager')
            ->setFactoryMethod('getUnitOfWork')
            ->setPublic(false)
        ;

        $definition = new Definition($repositoryClass);
        $definition->setArguments(array(
            new Reference($this->getContainerKey('manager')),
            $unitOfWorkDefinition,
            $this->getClassMetadataDefinition($classes['model']),
        ));

        return $definition;
    }

    /**
     * {@inheritdoc}
     */
    protected function getManagerServiceKey()
    {
        return 'doctrine.odm.mongodb.document_manager';
    }

    /**
     * {@inheritdoc}
     */
    protected function getClassMetadataClassname()
    {
        return 'Doctrine\\ODM\\MongoDB\\Mapping\\ClassMetadata';
    }
}
