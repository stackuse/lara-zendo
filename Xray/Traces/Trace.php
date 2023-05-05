<?php

namespace Libra\Zendo\Xray\Traces;

abstract class Trace
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Saves collected data
     *
     * @param array $data
     */
    abstract public function send(array $data);
}
