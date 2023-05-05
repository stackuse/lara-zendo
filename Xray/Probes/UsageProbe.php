<?php

namespace Libra\Zendo\Xray\Probes;

use Closure;
use const Omics\Xray\Probes\LARAVEL_START;

class UsageProbe extends Probe
{
    /**
     * @var float
     */
    protected float $requestStartTime;

    /**
     * @var float
     */
    protected float $requestEndTime;

    /**
     * @var array
     */
    protected array $startedMeasures = [];

    /**
     * @var array
     */
    protected array $measures = [];

    public function __construct()
    {
        if (isset($_SERVER['REQUEST_TIME_FLOAT'])) {
            $this->requestStartTime = $_SERVER['REQUEST_TIME_FLOAT'];
        } else {
            $this->requestStartTime = LARAVEL_START;
        }
    }

    /**
     * Utility function to measure the execution of a Closure
     *
     * @param string $label
     * @param Closure $closure
     * @param string|null $probe
     */
    public function measure(string $label, Closure $closure, string $probe = null)
    {
        $name = spl_object_hash($closure);
        $this->startMeasure($name, $label, $probe);
        $result = $closure();
        $params = is_array($result) ? $result : [];
        $this->stopMeasure($name, $params);
    }

    /**
     * Starts a measure
     *
     * @param string $name Internal name, used to stop the measure
     * @param string|null $label Public name
     * @param string|null $probe The source of the probe
     */
    public function startMeasure(string $name, string $label = null, string $probe = null)
    {
        $start = microtime(true);
        $this->startedMeasures[$name] = [
            'label' => $label ?: $name,
            'start' => $start,
            'probe' => $probe,
        ];
    }

    /**
     * Stops a measure
     *
     * @param string $name
     * @param array $params
     */
    public function stopMeasure(string $name, array $params = [])
    {
        if ($this->hasStartedMeasure($name)) {
            $end = microtime(true);
            $this->addMeasure(
                $this->startedMeasures[$name]['label'],
                $this->startedMeasures[$name]['start'],
                $end,
                $params,
                $this->startedMeasures[$name]['probe']
            );
            unset($this->startedMeasures[$name]);
        }
    }

    /**
     * Check a measure exists
     *
     * @param string $name
     * @return bool
     */
    public function hasStartedMeasure(string $name): bool
    {
        return isset($this->startedMeasures[$name]);
    }

    /**
     * Adds a measure
     *
     * @param string $label
     * @param float $start
     * @param float $end
     * @param array $params
     * @param string|null $probe
     */
    public function addMeasure(string $label, float $start, float $end, array $params = [], string $probe = null)
    {
        $this->measures[] = [
            'label' => $label,
            'start' => $start,
            'relative_start' => $start - $this->requestStartTime,
            'end' => $end,
            'relative_end' => $end - $this->requestEndTime,
            'duration' => $end - $start,
            'params' => $params,
            'probe' => $probe,
        ];
    }

    /**
     * Returns an array of all measures
     *
     * @return array
     */
    public function getMeasures(): array
    {
        return $this->measures;
    }

    /**
     * Returns the request start time
     *
     * @return float
     */
    public function getRequestStartTime(): float
    {
        return $this->requestStartTime;
    }

    /**
     * Returns the request end time
     *
     * @return float
     */
    public function getRequestEndTime(): float
    {
        return $this->requestEndTime;
    }

    /**
     * @return array
     */
    public function collect(): array
    {
        $this->requestEndTime = microtime(true);
        foreach (array_keys($this->startedMeasures) as $name) {
            $this->stopMeasure($name);
        }

        usort($this->measures, function ($a, $b) {
            if ($a['start'] === $b['start']) {
                return 0;
            }
            return $a['start'] < $b['start'] ? -1 : 1;
        });

        return [
            'start_time' => $this->requestStartTime,
            'end_time' => $this->requestEndTime,
            'measures' => array_values($this->measures),
            'duration' => $this->formatDuration($this->getRequestDuration()),
            'memory' => $this->formatMemory(memory_get_usage()),
        ];
    }

    /**
     * Returns the duration of a request
     *
     * @return float
     */
    public function getRequestDuration(): float
    {
        return $this->requestEndTime - $this->requestStartTime;
    }
}
