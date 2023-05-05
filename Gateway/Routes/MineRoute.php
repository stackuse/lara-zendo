<?php

namespace libra\zendo\Gateway\Routes;

use Closure;
use Illuminate\Http\Request;
use Libra\Zendo\Exceptions\BaseAuthException;

class MineRoute extends BaseRoute
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
        // step 0 判断是否在维护
        $this->block('open');

        // step 1 判断user_id 和 scope 是否正确
        $userId = $request->attributes->get('user_id');
        $authScope = $request->header("X-Authenticated-Scope");
        if ($userId && in_array($authScope, ['mine', 'user', 'mock'])) {
            return $next($request);
        }
        throw new BaseAuthException('token');
    }
}
