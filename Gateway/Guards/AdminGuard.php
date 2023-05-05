<?php

namespace Libra\Zendo\Gateway\Guards;

use Closure;
use Illuminate\Http\Request;
use Libra\Zendo\Exceptions\BaseAuthException;

class AdminGuard
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $client
     * @return mixed
     * @throws BaseAuthException
     */
    public function handle(Request $request, Closure $next, string $client = 'admin'): mixed
    {
        // 判断user_id 和 consumer 是否正确
        $userId = $request->attributes->get('user_id');
        $consumer = $request->header("X-Consumer-Username");
        if ($userId && $consumer === $client) {
            return $next($request);
        }
        throw new BaseAuthException('consumer');
        // 验证权限
        //        if ($request->attributes->get('role') !== 1) {
        //            $app = $request->header('X-Authenticated-App');
        //            $action = $request->route()->getAction();
        //            if (!empty($action['meta'])) {
        //                app('permit')->check($app, $action['meta']);
        //            }
        //        }
        //        return $next($request);
    }
}
