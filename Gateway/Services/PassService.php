<?php

namespace Libra\Zendo\Gateway\Services;

class PassService
{
    /**
     * @return mixed
     */
    public function getUserId(): mixed
    {
        return $this->get('user_id');
    }

    /**
     * @param string|null $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key = null, $default = null): mixed
    {
        if (app()->runningInConsole()) {
            return $default;
        }
        if ($key) {
            return app('request')->attributes->get($key);
        } else {
            return app('request')->attributes->all();
        }
    }

    /**
     * @return mixed
     */
    public function getRole(): mixed
    {
        return $this->get('role');
    }
}
