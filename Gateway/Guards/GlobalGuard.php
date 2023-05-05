<?php

namespace Libra\Zendo\Gateway\Guards;

use Closure;
use Illuminate\Http\Request;

/**
 * 全局，做一下转化
 * Oauth2的认证
 */
class GlobalGuard
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $userId = $request->header("X-Authenticated-Userid");
        // 穿透所有获取所有用户信息或不需要认证
        if (!$userId && $request->header('X-Authenticated-App-Key') === config('app.key')) {
            $userId = $request->header('X-Authenticated-Mock-Userid');
            $request->headers->set('X-Consumer-Username', $request->header('X-Authenticated-Mock-Consumer', 'mock'));
        }

        if ($userId) {
            $user = json_decode($userId, true);
            if (is_array($user)) {
                $request->attributes->add($user);
            }
        }
        return $next($request);
    }
}
