<?php

namespace Libra\Zendo\Gateway\Services;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Libra\Zendo\Exceptions\BaseAuthException;

class KongService
{
    /**
     * @param array $pass 可以标识用户身份的信息
     * @param string $scope 用来区分不同的身份
     * @return array
     * @throws BaseAuthException
     */
    public function grantToken(array $pass, string $scope = 'user'): array
    {
        try {
            $client = HttpClient::create();
            $oauthUrl = config('auth.kong.oauth2.url');
            $response = $client->request('POST', $oauthUrl, [
                'body' => [
                    'client_id' => config('auth.kong.oauth2.client_id'),
                    'client_secret' => config('auth.kong.oauth2.client_secret'),
                    'provision_key' => config('auth.kong.oauth2.provision_key'),
                    'grant_type' => 'password',
                    'scope' => $scope,
                    'authenticated_userid' => json_encode($pass),
                ],
                'verify_host' => false,
                'verify_peer' => false,
            ])->toArray();
            $response['expired_at'] = time() + $response['expires_in'];
            $response['refresh_expired_at'] = time() + config('auth.kong.oauth2.refresh_ttl');
            return $response;
        } catch (ExceptionInterface $e) {
            throw new BaseAuthException('custom', $e->getMessage());
        }
    }

    /**
     * @param string $refreshToken
     * @return array
     * @throws BaseAuthException
     */
    public function refreshToken(string $refreshToken): array
    {
        try {
            $client = HttpClient::create();
            $oauthUrl = config('auth.kong.oauth2.url');
            $response = $client->request('POST', $oauthUrl, [
                'body' => [
                    'grant_type' => 'refresh_token',
                    'client_id' => config('auth.kong.oauth2.client_id'),
                    'client_secret' => config('auth.kong.oauth2.client_secret'),
                    'refresh_token' => $refreshToken,
                ],
                'verify_peer' => false,
                'verify_host' => false,
            ])->toArray();
            $response['expired_at'] = time() + $response['expires_in'];
            $response['refresh_expired_at'] = time() + config('auth.kong.oauth2.refresh_ttl');
            return $response;
        } catch (ExceptionInterface $e) {
            throw new BaseAuthException('custom', $e->getMessage());
        }
    }
}
