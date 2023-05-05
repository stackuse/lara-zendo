<?php

namespace Libra\Zendo\Xray\Probes;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

/**
 * Abstract class for data probes
 */
abstract class Probe
{
    /**
     * @var array
     */
    protected array $config = [];

    protected Request|Command $request;

    /**
     * Called by the DebugBar when data needs to be collected
     *
     * @return array Collected data
     */
    abstract public function collect(): array;

    /**
     * @param array $config
     * @param Command|Request $request
     */
    public function start(array $config, Command|Request $request): void
    {
        $this->config = $config;
        $this->request = $request;
    }

    /**
     * @param float $seconds
     * @param int $precision
     * @return float
     */
    public function formatDuration(float $seconds, int $precision = 3): float
    {
        return round($seconds, $precision);
    }

    /**
     * @param float $size
     * @param int $precision
     * @return float
     */
    public function formatMemory(float $size, int $precision = 3): float
    {
        return round($size / (1024 * 1024), $precision);
    }
}
