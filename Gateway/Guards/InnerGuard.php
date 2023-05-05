<?php

namespace Libra\Zendo\Gateway\Guards;

use Closure;
use Illuminate\Http\Request;
use Libra\Zendo\Exceptions\BaseAuthException;

class InnerGuard
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
        if ($request->header('auth-key') !== config('auth.inner.key') || !config('auth.inner.key')) {
            throw new BaseAuthException('scope');
        }
        return $next($request);
    }
}
