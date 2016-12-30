<?php

namespace AshleyDawson\DomainEventDispatcherBundle\DataCollector;

use AshleyDawson\DomainEventDispatcher\DomainEventDispatcher;
use AshleyDawson\DomainEventDispatcher\EventInvocationMap\EventInvocationMapEventListener;
use AshleyDawson\DomainEventDispatcher\EventInvocationMap\EventInvocationMapEventListenerSet;
use AshleyDawson\DomainEventDispatcher\EventStorage\DisposableEventInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\LateDataCollectorInterface;

/**
 * Class DomainEventDataCollector
 *
 * @package AshleyDawson\DomainEventDispatcherBundle\DataCollector
 */
class DomainEventDataCollector extends DataCollector implements LateDataCollectorInterface
{
    /**
     * @var DomainEventDispatcher
     */
    private $domainEventDispatcher;

    /**
     * @var string
     */
    private $deferredEventName;

    /**
     * @var int
     */
    private $deferredEventPriority;

    /**
     * Constructor
     *
     * @param DomainEventDispatcher $domainEventDispatcher
     * @param string $deferredEventName
     * @param int $deferredEventPriority
     */
    public function __construct(DomainEventDispatcher $domainEventDispatcher, $deferredEventName, $deferredEventPriority)
    {
        $this->domainEventDispatcher = $domainEventDispatcher;
        $this->deferredEventName = $deferredEventName;
        $this->deferredEventPriority = $deferredEventPriority;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data['invocation_map'] = [];
        $this->data['immediate'] = 0;
        $this->data['deferred'] = 0;
        $this->data['deferred_events'] = 0;
        $this->data['immediate_events'] = 0;
        $this->data['immediate_elapsed_time'] = 0;
        $this->data['deferred_elapsed_time'] = 0;
        $this->data['deferred_event_name'] = $this->deferredEventName;
        $this->data['deferred_event_priority'] = $this->deferredEventPriority;
    }

    /**
     * {@inheritdoc}
     */
    public function lateCollect()
    {
        $invocationMap = $this
            ->domainEventDispatcher
            ->getEventInvocationMap();

        foreach ($invocationMap->getSets() as $set) {
            if (EventInvocationMapEventListenerSet::DISPOSITION_IMMEDIATE == $set->getDisposition()) {
                $this->data['immediate'] += count($set->getListeners());
                $this->data['immediate_elapsed_time'] += array_reduce($set->getListeners(), function ($carry, EventInvocationMapEventListener $l) {
                    return ($carry + $l->getExecutionTimeInMicroseconds());
                });

                $this->data['immediate_events'] ++;
            }

            if (EventInvocationMapEventListenerSet::DISPOSITION_DEFERRED == $set->getDisposition()) {
                $this->data['deferred'] += count($set->getListeners());
                $this->data['deferred_elapsed_time'] += array_reduce($set->getListeners(), function ($carry, EventInvocationMapEventListener $l) {
                    return ($carry + $l->getExecutionTimeInMicroseconds());
                });

                $this->data['deferred_events'] ++;
            }

            $this->data['invocation_map'][$set->getDisposition()]['events'][get_class($set->getEvent())]['event_instance'] = $set->getEvent();
            $this->data['invocation_map'][$set->getDisposition()]['events'][get_class($set->getEvent())]['is_disposable'] = ($set->getEvent() instanceof DisposableEventInterface);
            $this->data['invocation_map'][$set->getDisposition()]['events'][get_class($set->getEvent())]['listeners'] = array_map(function (EventInvocationMapEventListener $listener) {
                return [
                    'class' => get_class($listener->getListener()),
                    'execution_time_microseconds' => $listener->getExecutionTimeInMicroseconds(),
                    'is_typed' => $listener->getIsTyped(),
                ];
            }, $set->getListeners());
        }
    }

    /**
     * @return array
     */
    public function getInvocationMap()
    {
        return $this->data['invocation_map'];
    }

    /**
     * @return int
     */
    public function getImmediate()
    {
        return $this->data['immediate'];
    }

    /**
     * @return int
     */
    public function getDeferred()
    {
        return $this->data['deferred'];
    }

    /**
     * @return int
     */
    public function getNumberOfImmediateEvents()
    {
        return $this->data['immediate_events'];
    }

    /**
     * @return int
     */
    public function getNumberOfDeferredEvents()
    {
        return $this->data['deferred_events'];
    }

    /**
     * @return int
     */
    public function getImmediateElapsedTime()
    {
        return $this->data['immediate_elapsed_time'];
    }

    /**
     * @return int
     */
    public function getDeferredElapsedTime()
    {
        return $this->data['deferred_elapsed_time'];
    }

    /**
     * @return string
     */
    public function getDeferredEventName()
    {
        return $this->data['deferred_event_name'];
    }

    /**
     * @return int
     */
    public function getDeferredEventPriority()
    {
        return $this->data['deferred_event_priority'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ashley_dawson.domain_event_dispatcher';
    }
}