<?php

namespace AshleyDawson\DomainEventDispatcherBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class Configuration
 *
 * @package AshleyDawson\DomainEventDispatcherBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder
            ->root('ashley_dawson_domain_event_dispatcher');

        $rootNode
            ->children()
                ->scalarNode('dispatch_deferred_events_from_kernel_event')->defaultValue(KernelEvents::TERMINATE)->end()
                ->integerNode('dispatch_deferred_events_from_kernel_event_priority')->defaultValue(0)->end()
            ->end();

        return $treeBuilder;
    }
}