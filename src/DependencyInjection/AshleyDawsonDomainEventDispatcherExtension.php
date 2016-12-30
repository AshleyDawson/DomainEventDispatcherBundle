<?php

namespace AshleyDawson\DomainEventDispatcherBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class AshleyDawsonDomainEventDispatcherExtension
 *
 * @package AshleyDawson\DomainEventDispatcherBundle\DependencyInjection
 */
class AshleyDawsonDomainEventDispatcherExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processor = new Processor();

        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('ashleydawson.domain_event_dispatcher.dispatch_deferred_events_from_kernel_event',
            $config['dispatch_deferred_events_from_kernel_event']);

        $container->setParameter('ashleydawson.domain_event_dispatcher.dispatch_deferred_events_from_kernel_event_priority',
            $config['dispatch_deferred_events_from_kernel_event_priority']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }
}