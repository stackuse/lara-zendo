<?php

namespace Libra\Zendo\Exceptions;

class BaseAuthException extends BaseException
{
    protected array $baseErrors = [
        'scope' => [
            'status' => 401,
            'message' => '网关错误',
        ],
        'token' => [
            'status' => 401,
            'message' => '没有登录',
        ],
        'consumer' => [
            'status' => 401,
            'message' => '网关错误',
        ],
        'oauth' => [
            'status' => 401,
            'message' => '第三方授权认证失败',
        ],
        'block' => [
            'status' => 503,
            'message' => '系统维护中～',
        ],
    ];
}
