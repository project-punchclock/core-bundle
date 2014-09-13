<?php
namespace Volleyball\Bundle\UtilityBundle\DependencyInjection\Compiler;

use \Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\DependencyInjection\DefinitionDecorator;
use \Symfony\Component\DependencyInjection\Reference;

/**
 * Based on Sylius\Bundle\ResourceBundle\DependencyInjectin\Compiler\ObjectToIdentifierServicePass
 */
class ObjectToIdentifierServicePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('doctrine')) {
            $definition = new DefinitionDecorator('volleyball.form.type.object_to_identifier');
            $definition->addArgument(new Reference('doctrine'));
            $definition->addArgument('volleyball_entity_to_identifier');
            $definition->addTag('form.type', array('alias' => 'volleyball_entity_to_identifier'));

            $container->setDefinition('volleyball_entity_to_identifier', $definition);
        }

        if ($container->hasDefinition('doctrine_mongodb')) {
            $definition = new DefinitionDecorator('volleyball.form.type.object_to_identifier');
            $definition->addArgument(new Reference('doctrine_mongodb'));
            $definition->addArgument('volleyball_document_to_identifier');
            $definition->addTag('form.type', array('alias' => 'volleyball_document_to_identifier'));

            $container->setDefinition('volleyball_document_to_identifier', $definition);

            if (!$container->hasDefinition('volleyball_entity_to_identifier')) {
                $container->setAlias('volleyball_entity_to_identifier', 'volleyball_document_to_identifier');
            }
        }
    }
}
