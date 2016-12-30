<?php

namespace AshleyDawson\DomainEventDispatcherBundle\EventListener;

use AshleyDawson\DomainEventDispatcher\DomainEventDispatcherInterface;

/**
 * Class DispatchDeferredEventsListener
 *
 * @package AshleyDawson\DomainEventDispatcherBundle\EventListener
 */
class DispatchDeferredEventsListener
{
    /**
     * @var DomainEventDispatcherInterface
     */
    private $domainEventDispatcher;

    /**
     * Constructor
     *
     * @param DomainEventDispatcherInterface $domainEventDispatcher
     */
    public function __construct(DomainEventDispatcherInterface $domainEventDispatcher)
    {
        $this->domainEventDispatcher = $domainEventDispatcher;
    }

    /**
     * Dispatch the deferred domain events
     */
    public function dispatchDeferredEvents()
    {
        $this->domainEventDispatcher->dispatchDeferred();
    }
}
