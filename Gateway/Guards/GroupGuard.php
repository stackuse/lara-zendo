<?php

namespace Libra\Zendo\Gateway\Guards;

use Closure;
use Illuminate\Http\Request;
use Libra\Zendo\Exceptions\BaseAuthException;

class GroupGuard
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $consumer
     * @return mixed
     * @throws BaseAuthException
     */
    public function handle(Request $request, Closure $next, string $consumer): mixed
    {
        // 判断user_id 和 consumer 是否正确
        $userId = $request->attributes->get('user_id');
        $authConsumer = $request->header("X-Consumer-Username");
        // 用户是否登录不强制
        if ($consumer === 'open') {

        } else {
            if ($userId && $consumer === $authConsumer) {
                return $next($request);
            }
        }
        throw new BaseAuthException('consumer');
    }
}
