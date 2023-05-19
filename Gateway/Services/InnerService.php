<?php

namespace Libra\Zendo\Gateway\Services;

use Libra\Zendo\Exceptions\BaseAuthException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class InnerService
{
    public function fetch(string $path, array $params, string $method = 'post'): array
    {
        try {
            $host = config('auth.inner.host');
            $client = HttpClient::create([
                'base_uri' => $host,
                'headers' => [
                    'auth-key' => config('auth.inner.key'),
                ],
                'verify_host' => false,
                'verify_peer' => false,
            ]);
            if ($method === 'post') {
                $options = [
                    'query' => $params,
                ];
            } else {
                $options = [
                    'json' => $params,
                ];
            }

            return $client->request($method, $path, $options)->toArray();
        } catch (ExceptionInterface $e) {
            throw new BaseAuthException('custom', $e->getMessage());
        }
    }
}
