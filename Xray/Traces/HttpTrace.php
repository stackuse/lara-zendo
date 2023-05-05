<?php

namespace Libra\Zendo\Xray\Traces;

use Omics\Guard\Exceptions\GuardException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

/**
 * Stores collected data into http
 */
class HttpTrace extends Trace
{
    /**
     * {@inheritdoc}
     */
    public function send($data)
    {
        try {
            $client = HttpClient::create();
            $url = $this->config['url'];
            $response = $client->request('POST', $url, [
                'headers' => [
                    'auth-key' => $this->config['key'],
                ],
                'json' => $data,
                'verify_host' => false,
                'verify_peer' => false,
            ]);
        } catch (ExceptionInterface $e) {
            throw new GuardException('custom', $e->getMessage());
        }
    }
}
