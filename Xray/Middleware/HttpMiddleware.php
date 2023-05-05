<?php

namespace Libra\Zendo\Xray\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpMiddleware
{
    private array $xray = [];

    public function handle(Request $request, Closure $next)
    {
        $enable = $this->enable($request);
        $enable && app('xray')->boot($request);
        $response = $next($request);
        if ($enable && $response instanceof JsonResponse) {
            $this->xray = app('xray')->collect();
            if (config('xray.action.show')) {
                $data = $response->getData();
                $data->_xray = $this->xray;
                $response->setData($data);
            }
        }
        return $response;
    }

    /**
     * 可以通过配置 或 URL来控制是否显示xray信息
     * @param Request $request
     * @return bool
     */
    private function enable(Request $request): bool
    {
        if (config('xray.action.show') || config('xray.action.trace') || $request->get('xray_key') === config('app.key')) {
            return true;
        }
        return false;
    }

    /**
     * 上报数据
     * @param Request $request
     * @param Response $response
     */
    public function terminate(Request $request, Response $response): void
    {
        if (config('xray.action.trace')) {
            app('xray')->trace();
        }
    }
}
