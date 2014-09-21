<?php
namespace Volleyball\Bundle\UtilityBundle\DependencyInjection;

use \Symfony\Component\Config\Definition\Builder\TreeBuilder;
use \Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('volleyball_resource');

        $this->addBreadcrumbsSection($rootNode);

        return $treeBuilder;
    }
    
    private function addBreadcrumbsSection($node)
    {
        $node->
            children()
                ->scalarNode("breadcrumbs_separator")
                    ->defaultValue("/")
                    ->end()
                ->scalarNode("breadcrumbs_separatorClass")
                    ->defaultValue("separator")
                    ->end()
                ->scalarNode("breadcrumbs_listId")
                    ->defaultValue("volleyball-breadcrumbs")
                    ->end()
                ->scalarNode("breadcrumbs_listClass")
                    ->defaultValue("breadcrumb")
                    ->end()
                ->scalarNode("breadcrumbs_itemClass")
                    ->defaultValue("")
                    ->end()
                ->scalarNode("breadcrumbs_linkRel")
                    ->defaultValue("")
                    ->end()
                ->scalarNode("breadcrumbs_locale")
                    ->defaultNull()
                    ->end()
                ->scalarNode("breadcrumbs_translation_domain")
                    ->defaultNull()
                    ->end()
                ->scalarNode("breadcrumbs_viewTemplate")
                    ->defaultValue("VolleyballResourceBundle:Base:breadcrumbs.html.twig")
                    ->end()
                ->end();
    }
}
