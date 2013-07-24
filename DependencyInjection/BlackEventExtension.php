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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Class BlackEventExtension
 *
 * @package Black\Bundle\EventBundle\DependencyInjection
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class BlackEventExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if (!isset($config['db_driver'])) {
            throw new \InvalidArgumentException('You must provide the black_event.db_driver configuration');
        }

        try {
            $loader->load(sprintf('%s.xml', $config['db_driver']));
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException(
                sprintf('The db_driver "%s" is not supported by engine', $config['db_driver'])
            );
        }

        $this->remapParametersNamespaces(
            $config,
            $container,
            array(
                ''  => array(
                    'db_driver'             => 'black_event.db_driver',
                    'event_class'           => 'black_event.model.event.class',
                    'subevent_class'        => 'black_event.model.subevent.class',
                    'postaladdress_class'   => 'black_event.postaladdress.model.class',
                    'event_manager'         => 'black_event.event.manager'
                )
            )
        );

        if (!empty($config['event'])) {
            $this->loadEvent($config['event'], $container, $loader);
        }

        if (!empty($config['subevent'])) {
            $this->loadSubEvent($config['subevent'], $container, $loader);
        }

        if (!empty($config['postaladdress'])) {
            $this->loadPostalAddress($config['postaladdress'], $container, $loader);
        }
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param XmlFileLoader    $loader
     */
    private function loadEvent(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('event.xml');

        $this->remapParametersNamespaces(
            $config,
            $container,
            array(
                'form' => 'black_event.event.form.%s',
            )
        );
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param XmlFileLoader    $loader
     */
    private function loadSubEvent(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('sub_event.xml');

        $this->remapParametersNamespaces(
            $config,
            $container,
            array(
                'form' => 'black_event.subevent.form.%s',
            )
        );
    }

    private function loadPostalAddress(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('postalAddress.xml');

        $this->remapParametersNamespaces(
            $config,
            $container,
            array(
                'form' => 'black_event.postaladdress.form.%s',
            )
        );
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param array            $map
     */
    protected function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
        foreach ($map as $name => $paramName) {
            if (array_key_exists($name, $config)) {
                $container->setParameter($paramName, $config[$name]);
            }
        }
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param array            $namespaces
     */
    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $ns => $map) {

            if ($ns) {
                if (!array_key_exists($ns, $config)) {
                    continue;
                }
                $namespaceConfig = $config[$ns];
            } else {
                $namespaceConfig = $config;
            }
            if (is_array($map)) {
                $this->remapParameters($namespaceConfig, $container, $map);
            } else {
                foreach ($namespaceConfig as $name => $value) {
                    $container->setParameter(sprintf($map, $name), $value);
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return 'black_event';
    }
}
