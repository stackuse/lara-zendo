<?php

namespace Libra\Zendo\Gateway\Guards;

use Closure;
use Illuminate\Http\Request;
use Libra\Zendo\Exceptions\BaseAuthException;

class ScopeGuard
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $scope
     * @return mixed
     * @throws BaseAuthException
     */
    public function handle(Request $request, Closure $next, string $scope): mixed
    {
        // 判断user_id 和 consumer 是否正确
        $userId = $request->attributes->get('user_id');
        $consumer = $request->header("X-Consumer-Username");
        // 用户是否登录不强制
        if ($scope === 'open') {

        } else {
            if ($userId && $consumer === $scope) {
                return $next($request);
            }
        }
        throw new BaseAuthException('consumer');
    }
}
