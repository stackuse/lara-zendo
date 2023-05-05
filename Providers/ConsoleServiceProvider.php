<?php

namespace Libra\Zendo\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Providers\ArtisanServiceProvider;
use Illuminate\Foundation\Providers\ComposerServiceProvider;
use Illuminate\Support\AggregateServiceProvider;
use Libra\Dbal\Providers\MigrationServiceProvider;

class ConsoleServiceProvider extends AggregateServiceProvider implements DeferrableProvider
{
    /**
     * The provider class names.
     *
     * @var string[]
     */
    protected $providers = [
        ArtisanServiceProvider::class,
        MigrationServiceProvider::class,
        ComposerServiceProvider::class,
    ];
}
