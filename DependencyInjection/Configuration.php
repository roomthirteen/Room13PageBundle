<?php

namespace Room13\PageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('room13_page');

        $rootNode
            ->children()
                ->booleanNode('admin')->cannotBeEmpty()->defaultFalse()->end()
                ->booleanNode('menu')->cannotBeEmpty()->defaultTrue()->end()
                ->booleanNode('templating')->cannotBeEmpty()->defaultTrue()->end()
                ->booleanNode('twig')->cannotBeEmpty()->defaultTrue()->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('page_full')->defaultValue('Room13PageBundle:Page:full.html.twig')->cannotBeEmpty()->end()
                            ->scalarNode('page_ajax')->defaultValue('Room13PageBundle:Page:ajax.html.twig')->cannotBeEmpty()->end()
                        ->end()
                ->end()
            ->end()
        ;


        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
