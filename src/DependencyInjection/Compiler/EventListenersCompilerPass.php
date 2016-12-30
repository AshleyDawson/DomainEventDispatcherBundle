<?php

namespace AshleyDawson\DomainEventDispatcherBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class EventListenersCompilerPass
 *
 * @package AshleyDawson\DomainEventDispatcherBundle\DependencyInjection\Compiler
 */
class EventListenersCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('ashley_dawson.domain_event_dispatcher')) {
            return;
        }

        $definition = $container
            ->findDefinition('ashley_dawson.domain_event_dispatcher');

        $taggedServices = $container
            ->findTaggedServiceIds('ashley_dawson.domain_event_listener');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addListener', array(new Reference($id)));
        }
    }
}