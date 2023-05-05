<?php

namespace Libra\Zendo\Xray\Services;

use Libra\Zendo\Xray\Factory\ProbeFactory;
use Libra\Zendo\Xray\Factory\TraceFactory;

class XrayService
{
    protected array $data = [];

    protected array $probes = [];

    /**
     * @var ProbeFactory $probeFactory
     */
    protected ProbeFactory $probeFactory;

    protected TraceFactory $traceFactory;

    public function __construct()
    {
        $this->probeFactory = new ProbeFactory();
        $this->traceFactory = new TraceFactory();
    }

    public function boot($request): void
    {
        $this->probes = $this->probeFactory->addCollectors($request);
    }

    public function trace(): void
    {
        $this->collect();
        $this->traceFactory->save($this->data);
    }

    public function collect(): array
    {
        $this->data = $this->probeFactory->collect();
        return $this->data;
    }

    /**
     * Starts a measure
     *
     * @param string $name Internal name, used to stop the measure
     * @param null $label Public name
     * @param null $probe The source of the probe
     */
    public function startMeasure(string $name, $label = null, $probe = null)
    {
        $this->probes['time']?->startMeasure($name, $label, $probe);
    }

    /**
     * Stops a measure
     *
     * @param string $name
     * @param array $params
     */
    public function stopMeasure(string $name, array $params = [])
    {
        $this->probes['time']?->stopMeasure($name, $params);
    }
}
