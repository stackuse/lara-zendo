<?php

namespace Libra\Zendo\Xray\Probes;

use Carbon\Carbon;
use Illuminate\Routing\Route;

class RequestProbe extends Probe
{
    /**
     * @return array
     */
    public function collect(): array
    {
        $request = $this->request;
        if (app()->runningInConsole()) {
            return [
                'host' => 'localhost',
                'uri' => '',
                'method' => 'console',
                'controller' => get_class($request),
                'action' => 'handle',
                'time' => Carbon::now()->format('Y-m-d H:i:s'),
                'clientIp' => gethostname(),
            ];
        } else {
            /** @var Route $route */
            $route = $request->route();
            if ($route) {
                return [
                    'host' => $request->getHttpHost(),
                    'uri' => $request->getPathInfo(),
                    'method' => $request->getMethod(),
                    'user' => app('request')->attributes->all(),
                    'action' => $route->getActionName(),
                    'time' => Carbon::createFromTimestamp($_SERVER['REQUEST_TIME_FLOAT'])->format('Y-m-d H:i:s'),
                    'clientIp' => $request->getClientIp(),
                    'query' => $request->query->all(),
                    'headers' => $request->header(),
                    'attributes' => $request->attributes->all(),
                ];
            } else {
                return [];
            }
        }
    }
}
