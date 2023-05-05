<?php

namespace Libra\Zendo\Providers;

use Illuminate\Routing\RoutingServiceProvider;

class RouteServiceProvider extends RoutingServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerRouter();
        $this->registerUrlGenerator();
    }
}
