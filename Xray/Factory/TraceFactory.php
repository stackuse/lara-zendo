<?php

namespace Libra\Zendo\Xray\Factory;

use Exception;
use Libra\Zendo\Xray\Traces\FileTrace;
use Libra\Zendo\Xray\Traces\HttpTrace;
use Libra\Zendo\Xray\Traces\RedisTrace;

class TraceFactory
{
    public function save(array $data)
    {
        try {
            $isSample = false;
            $sample = config('xray.trace.sample');
            if ($sample['time'] <= $data['time']['duration']) {
                $isSample = true;
            } elseif ($sample['sql'] <= count($data['model']['statements'])) {
                $isSample = true;
            } elseif ($sample['memory'] <= $data['host']['memory']) {
                $isSample = true;
            } elseif ($sample['rate'] === 1 || mt_rand(1, $sample['rate']) === 1) {
                $isSample = true;
            }
            if ($isSample) {
                $traceConfig = config('xray.trace');
                $driver = $traceConfig['driver'];
                $trace = match ($driver) {
                    'http' => new HttpTrace($traceConfig[$driver]),
                    'redis' => new RedisTrace($traceConfig[$driver]),
                    default => new FileTrace($traceConfig['file']),
                };
                $trace->send($data);
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
