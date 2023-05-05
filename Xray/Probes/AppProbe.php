<?php

namespace Libra\Zendo\Xray\Probes;

class AppProbe extends Probe
{
    /**
     * @return array
     */
    public function collect(): array
    {
        return [
            'name' => config('app.name'),
            'env' => config('app.env'),
            'url' => config('app.url'),
            'root' => app()->basePath(),
            'laravel' => app()->version(),
            // 'file_count' => count(get_included_files()),
            // 'files' => get_included_files(),
            // 'backtrace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS),
        ];
    }
}
