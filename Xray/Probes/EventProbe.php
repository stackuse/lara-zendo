<?php

namespace Libra\Zendo\Xray\Probes;

use Illuminate\Support\Facades\Event;
use Libra\Zendo\Stream\Event as AppEvent;

class EventProbe extends Probe
{
    protected array $events = [];

    public function __construct()
    {
        Event::listen(AppEvent::class, [$this, 'handleEvent']);
    }

    public function collect(): array
    {
        return $this->events;
    }

    public function handleEvent($event)
    {
        $this->events[get_class($event)][] = $event;
    }
}
