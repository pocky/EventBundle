<?php

/*
 * This file is part of the Black package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Black\Bundle\EventBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * Class Configuration
 *
 * @package Black\Bundle\EventBundle\DependencyInjection
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('black_event');

        $supportedDrivers = array('mongodb', 'orm');

        $rootNode
            ->children()

                ->scalarNode('db_driver')
                    ->isRequired()
                    ->validate()
                    ->ifNotInArray($supportedDrivers)
                        ->thenInvalid('The database driver must be either \'mongodb\', \'orm\'.')
                    ->end()
                ->end()
                ->scalarNode('event_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('event_manager')->defaultValue('Black\\Bundle\\EventBundle\\Doctrine\\EventManager')->end()
            ->end();

        $this->addEventSection($rootNode);
        $this->addSubEventSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
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
                                ->scalarNode('name')->defaultValue('black_event_event')->end()
                                ->scalarNode('type')->defaultValue(
                                    'Black\\Bundle\\EventBundle\\Form\\Type\\EventType'
                                )->end()
                                ->scalarNode('handler')->defaultValue(
                                    'Black\\Bundle\\EventBundle\\Form\\Handler\\EventFormHandler'
                                )->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addSubEventSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('subevent')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('name')->defaultValue('black_event_sub_event')->end()
                                ->scalarNode('type')->defaultValue(
                                    'Black\\Bundle\\EventBundle\\Form\\Type\\SubEventType'
                                )->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
