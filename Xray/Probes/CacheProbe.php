<?php

namespace Libra\Zendo\Xray\Probes;

use Illuminate\Cache\Events\CacheHit;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Cache\Events\KeyForgotten;
use Illuminate\Cache\Events\KeyWritten;
use Illuminate\Support\Facades\Event;

class CacheProbe extends Probe
{
    protected array $events = [];

    protected array $cacheEvents = [
        CacheHit::class,
        CacheMissed::class,
        KeyWritten::class,
        KeyForgotten::class,
    ];

    public function __construct()
    {

        foreach ($this->cacheEvents as $event) {
            Event::listen($event, [$this, 'handleEvent']);
        }
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
