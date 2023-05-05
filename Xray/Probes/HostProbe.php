<?php

namespace Libra\Zendo\Xray\Probes;

/**
 * Collects info about host
 */
class HostProbe extends Probe
{
    /**
     * @return array
     */
    public function collect(): array
    {
        $hostname = gethostname();
        return [
            'name' => $hostname,
            'ipv4' => gethostbyname($hostname),
            'version' => PHP_VERSION,
            'interface' => PHP_SAPI,
        ];
    }
}
