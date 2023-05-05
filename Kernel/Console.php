<?php

namespace Libra\Zendo\Kernel;

use Illuminate\Foundation\Console\Kernel;

class Console extends Kernel
{
    protected function commands()
    {
        $this->app->terminate();
    }
}
