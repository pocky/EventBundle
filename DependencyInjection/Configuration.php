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

                    ->arrayNode('class')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->arrayNode('model')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('config')->defaultValue('Blackroom\\Bundle\\ERPBundle\\Document\\Event')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()

                ->end()
        ;

        return $treeBuilder;
    }
}
