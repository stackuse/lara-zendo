<?php

namespace Libra\Zendo\Providers;

use Dotenv\Dotenv;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Libra\Dbal\Providers\DatabaseServiceProvider;
use Libra\Zendo\Commands\OpcacheClearCommand;
use Libra\Zendo\Commands\OpcacheCommand;
use Libra\Zendo\Gateway\Services\InnerService;
use Libra\Zendo\Gateway\Services\KongService;
use Libra\Zendo\Gateway\Services\PassService;
use Libra\Zendo\Gateway\Services\PermitService;
use Libra\Zendo\Kernel\Console;
use Libra\Zendo\Kernel\Exception;
use Libra\Zendo\Kernel\Http;
use Libra\Zendo\Xray\XrayServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->register(DatabaseServiceProvider::class);
        $this->app->register(XrayServiceProvider::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                OpcacheCommand::class,
                OpcacheClearCommand::class,
            ]);
        }

        if (!$this->app->routesAreCached()) {
            $basePath = config('app.base_path');
            if ($this->app->environment('local') && $basePath) {
                Router::macro('setBasePath', function ($path) {
                    $this->updateGroupStack(['prefix' => $path]);
                });
                Route::setBasePath($basePath);
            }
        }

        $moduleRootPath = $this->app->basePath('app');
        $modules = scandir($moduleRootPath);
        foreach ($modules as $module) {
            if (!in_array($module, ['.', '..'])) {
                // register module service
                $moduleServiceProvider = "\\App\\{$module}\\Providers\\ServiceProvider";
                if (class_exists($moduleServiceProvider)) {
                    $this->app->register($moduleServiceProvider);
                }

                // register module event
                $eventServiceProvider = "\\App\\{$module}\\Providers\\EventServiceProvider";
                if (class_exists($eventServiceProvider)) {
                    $this->app->register($eventServiceProvider);
                }
            }
        }
    }

    public function register()
    {
        if (!$this->app->configurationIsCached()) {
            $this->app->afterLoadingEnvironment(function () {
                // load local env
                $localEnvFile = $this->app->environmentPath() . DIRECTORY_SEPARATOR . $this->app->environmentFile() . '.local';
                if (file_exists($localEnvFile)) {
                    Dotenv::create(Env::getRepository(), $this->app->environmentPath(), $this->app->environmentFile() . '.local')->safeLoad();
                }

                // load app_env 对应的env文件，如果有--env或APP_ENV，则不能重复加载
                $appEnvConfig = $this->app->environmentFile() . '.' . Env::get('APP_ENV');
                if ($this->app->environmentFile() !== $appEnvConfig && file_exists($this->app->environmentPath() . DIRECTORY_SEPARATOR . $appEnvConfig)) {
                    Dotenv::create(Env::getRepository(), $this->app->environmentPath(), $appEnvConfig)->safeLoad();
                }
            });
        };
        $this->app->singleton(HttpKernel::class, Http::class);

        $this->app->singleton(ConsoleKernel::class, Console::class);

        $this->app->singleton(ExceptionHandler::class, Exception::class);

        $this->app->singleton('guard', function () {
            return new KongService();
        });

        $this->app->singleton('pass', function () {
            return new PassService();
        });

        $this->app->singleton('permit', function () {
            return new PermitService();
        });

        $this->app->singleton('inner', function () {
            return new InnerService();
        });

        $this->app->register(RouteServiceProvider::class);
        // @notice 目前有问题
        $this->app->register(ConsoleServiceProvider::class);
    }
}
