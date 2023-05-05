<?php

namespace Libra\Zendo\Gateway\Services;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Libra\Zendo\Exceptions\BaseAuthException;

class PermitService
{
    public function check(string $app, array $meta): array
    {
        try {
            $client = HttpClient::create();
            $oauthUrl = config('auth.permit.oauth_url');
            $response = $client->request('GET', $oauthUrl, [
                'body' => [
                    'app' => $app,
                ],
                'verify_host' => false,
                'verify_peer' => false,
            ])->toArray();
            $response['expired_at'] = time() + $response['expires_in'];
            return $response;
        } catch (ExceptionInterface $e) {
            throw new BaseAuthException('custom', $e->getMessage());
        }
    }
}
