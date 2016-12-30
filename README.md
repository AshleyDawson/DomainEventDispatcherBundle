Domain Event Dispatcher Bundle
==============================

Brings the [domain event dispatcher](https://github.com/AshleyDawson/DomainEventDispatcher) (singleton) to [Symfony](https://symfony.com/) projects.
For full usage instructions, [please see the full documentation](https://github.com/AshleyDawson/DomainEventDispatcher/blob/master/README.md) 
shipped with the library.

Installation
------------

Install the bundle via [Composer](https://getcomposer.org/):

```
$ composer require ashleydawson/domain-event-dispatcher-bundle
```

Then, register the bundle with the Symfony kernel:

```php
$bundles = [
    // ...
    new AshleyDawson\DomainEventDispatcherBundle\AshleyDawsonDomainEventDispatcherBundle(),
];
```

Configuration
-------------

[Deferred events](https://github.com/AshleyDawson/DomainEventDispatcher/blob/master/README.md#deferred-events) are 
configured to be dispatched from the Symfony `kernel.terminate` kernel event. To change this, add the following to 
your `app/config/config.yml` file:

```yml
ashley_dawson_domain_event_dispatcher:
    dispatch_deferred_events_from_kernel_event: kernel.terminate
    dispatch_deferred_events_from_kernel_event_priority: 0
```

Usage
-----

Please refer to the full documentation for an in-depth look at how to use the domain event dispatcher. However, please 
find a simple example below:

Create an event:

```php
<?php

namespace AppBundle\DomainEvent;

class MyDomainEvent
{
    private $myEntityId;
    
    public function __construct($myEntityId)
    {
        $this->myEntityId = $myEntityId;
    }
    
    public function getMyEntityId()
    {
        return $this->myEntityId;
    }
}
```

Create a listener:

```php
<?php

namespace AppBundle\DomainEventListener;

use AppBundle\DomainEvent\MyDomainEvent;

class MyDomainEventListener
{
    public function __invoke(MyDomainEvent $event)
    {
        // Do something with the event...
    }
}
```

Add the listener to the event dispatcher via the [Symfony Dependency Injection Container](https://symfony.com/doc/current/components/dependency_injection.html)
using the tag `ashley_dawson.domain_event_listener`:

```yml
# app/config/services.yml

services:
    app.my_domain_event_listener:
        class: AppBundle\DomainEventListener\MyDomainEventListener
        tags:
            - { name: ashley_dawson.domain_event_listener }
```

Dispatch an event from your model:

```php
<?php

namespace AppBundle\Entity;

use AshleyDawson\DomainEventDispatcher\DomainEventDispatcher;
use AppBundle\DomainEvent\MyDomainEvent;

class MyEntity
{
    private $id;
    
    public function mySpecialCommand()
    {
        DomainEventDispatcher::getInstance()->dispatch(
            new MyDomainEvent($this->id)
        );
    }
}
```

Symfony Profiler
----------------

The map of events that have been deferred/dispatched during a request can be found in the Symfony Profiler. Simply click
on the domain events icon and the profile screen containing the map will be displayed.

Toolbar Info:

![Toolbar](https://raw.githubusercontent.com/AshleyDawson/DomainEventDispatcherBundle/master/src/Resources/docs/img/domain-event-dispatcher-toolbar-info.jpg)

Full Profiler Screen:

![Profiler Screen](https://raw.githubusercontent.com/AshleyDawson/DomainEventDispatcherBundle/master/src/Resources/docs/img/domain-event-dispatcher-full.jpg)