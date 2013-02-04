<?php

/*
 * This file is part of the Blackengine package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Blackroom\Bundle\EventBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

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
        $rootNode = $treeBuilder->root('blackroom_event');

        $supportedDrivers = array('mongodb');

        $rootNode
            ->children()

                ->scalarNode('db_driver')
                    ->isRequired()
                    ->validate()
                    ->ifNotInArray($supportedDrivers)
                        ->thenInvalid('The database driver must be either \'mongodb\'.')
                    ->end()
                ->end()

                ->scalarNode('event_class')->isRequired()->cannotBeEmpty()->end()

            ->end()
        ;

        $this->addEventSection($rootNode);

        return $treeBuilder;
    }

    private function addEventSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('event')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('name')->defaultValue('blackroom_event_event')->end()
                                ->scalarNode('type')->defaultValue('Blackroom\\Bundle\\EventBundle\\Form\\Type\\EventType')->end()
                                ->scalarNode('handler')->defaultValue('Blackroom\\Bundle\\EventBundle\\Form\\Handler\\EventFormHandler')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
