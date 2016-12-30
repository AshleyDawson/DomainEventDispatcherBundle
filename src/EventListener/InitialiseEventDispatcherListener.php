<?php

namespace AshleyDawson\DomainEventDispatcherBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class InitialiseEventDispatcherListener
 *
 * @package AshleyDawson\DomainEventDispatcherBundle\EventListener
 */
class InitialiseEventDispatcherListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Trigger container compilation that will add tagged listeners to event dispatcher
     */
    public function triggerContainerCompilation()
    {
        $this->container->get('ashley_dawson.domain_event_dispatcher');
    }
}
