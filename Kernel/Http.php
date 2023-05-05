<?php

namespace Libra\Zendo\Kernel;

use Illuminate\Foundation\Http\Kernel;
use Libra\Zendo\Gateway\Guards\ClientGuard;
use Libra\Zendo\Gateway\Guards\GlobalGuard;

class Http extends Kernel
{

    protected $middleware = [
        GlobalGuard::class
    ];

    protected $middlewareAliases = [
        'client' => ClientGuard::class
    ];

    protected $middlewarePriority = [];

    protected $middlewareGroups = [
        'admin' => [
            'client:admin'
        ],
        'mine' => [
            'client:user'
        ],
        'open' => [],
        'inner' => [],
    ];
}
