<?php

namespace Libra\Zendo\Xray;

use Libra\Zendo\Providers\BaseServiceProvider;
use Libra\Zendo\Xray\Middleware\HttpMiddleware;
use Libra\Zendo\Xray\Services\XrayService;

class XrayServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        if (!$this->app->configurationIsCached()) {
            $configPath = __DIR__ . '/config/xray.php';
            $this->mergeConfigFrom($configPath, 'xray');
        }
        // 不能在这里判断是否需要xray，这里无法通过URL来控制是否显示xray信息
        $this->app->singleton('xray', function () {
            return new XrayService();
        });
        $this->kernel->pushMiddleware(HttpMiddleware::class);
    }
}
