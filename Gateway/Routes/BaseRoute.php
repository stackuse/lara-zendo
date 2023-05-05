<?php

namespace libra\zendo\Gateway\Routes;

use Libra\Zendo\Exceptions\BaseAuthException;

class BaseRoute
{

    /**
     * 屏蔽某部分路由
     * @param string $scope
     * @return void
     * @throws BaseAuthException
     */
    public function block(string $scope): void
    {
        $blockScopes = config('app.route.block_scopes');
        if ($blockScopes && in_array($scope, $blockScopes)) {
            throw new BaseAuthException('block');
        }
    }
}
