<?php

namespace Libra\Zendo\Xray\Factory;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Libra\Zendo\Xray\Probes\AppProbe;
use Libra\Zendo\Xray\Probes\CacheProbe;
use Libra\Zendo\Xray\Probes\EventProbe;
use Libra\Zendo\Xray\Probes\HostProbe;
use Libra\Zendo\Xray\Probes\ModelProbe;
use Libra\Zendo\Xray\Probes\Probe;
use Libra\Zendo\Xray\Probes\RequestProbe;
use Libra\Zendo\Xray\Probes\UsageProbe;

class ProbeFactory
{
    public array $probes = [
        'host' => HostProbe::class,
        'app' => AppProbe::class,
        'request' => RequestProbe::class,
        'model' => ModelProbe::class,
        'event' => EventProbe::class,
        'cache' => CacheProbe::class,
        'usage' => UsageProbe::class,
    ];
    protected array $enableCollectors = [];

    public function addCollectors(Request|Command $request): array
    {
        $probeConfig = config('xray.probes');
        foreach ($this->probes as $name => $probe) {
            if (!empty($probeConfig[$name]['enable'])) {
                /** @var Probe $collectObj */
                $collectObj = new $probe;
                $collectObj->start($probeConfig[$name], $request);
                $this->enableCollectors[$name] = $collectObj;
            }
        }
        return $this->enableCollectors;
    }

    public function collect(): array
    {
        $data = [];
        foreach ($this->enableCollectors as $name => $probe) {
            if (empty($data[$name])) {
                $data[$name] = $probe->collect();
            }
        }
        return $data;
    }
}
