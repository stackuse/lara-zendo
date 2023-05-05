<?php

namespace Libra\Zendo\Xray\Traces;

/**
 * Stores collected data into Redis
 */
class RedisTrace extends Trace
{
    /**
     * {@inheritdoc}
     */
    public function send(array $data)
    {
        app('redis')->connection($this->config['connection'])->publish($this->config['channel'], json_encode($data));
    }
}
