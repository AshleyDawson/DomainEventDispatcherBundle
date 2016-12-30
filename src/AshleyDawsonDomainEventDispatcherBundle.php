<?php

namespace AshleyDawson\DomainEventDispatcherBundle;

use AshleyDawson\DomainEventDispatcherBundle\DependencyInjection\Compiler\EventListenersCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AshleyDawsonDomainEventDispatcherBundle
 *
 * @package AshleyDawson\DomainEventDispatcherBundle
 */
class AshleyDawsonDomainEventDispatcherBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new EventListenersCompilerPass());
    }
}
