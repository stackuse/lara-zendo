<?php

namespace Libra\Zendo\Gateway\Guards;

use Closure;
use Illuminate\Http\Request;
use Libra\Zendo\Exceptions\BaseAuthException;

class OpenGuard
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws BaseAuthException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // 判断user_id 和 consumer 是否正确
        $userId = $request->attributes->get('user_id');
        $consumer = $request->header("X-Consumer-Username");

        if ($userId && in_array($consumer, ['user', 'mock'])) {
            return $next($request);
        }
        throw new BaseAuthException('consumer');
    }
}
