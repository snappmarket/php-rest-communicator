<?php

namespace SnappMarket\Communicator;

use GuzzleHttp\Client;

class Communicator
{
    const DEFAULT_TRIES_VALUE = 1;

    const METHOD_POST = 'POST';
    const METHOD_GET  = 'GET';


    protected $client;


    protected $tries = self::DEFAULT_TRIES_VALUE;



    public function __construct(string $baseUrl, array $headers = [])
    {
        $options = [
             'base_uri' => $baseUrl,
             'headers'  => $headers,
             //'http_errors' => false,
        ];

        $this->client = new Client($options);
    }



    public function post(string $uri, array $parameters = [], array $headers = [])
    {
        return $this->request(static::METHOD_POST, $uri, $parameters, $headers);
    }



    public function get(string $uri, array $parameters = [], array $headers = [])
    {
        return $this->request(static::METHOD_GET, $uri, $parameters, $headers);
    }



    public function request(string $method, string $uri, array $parameters = [], array $headers = [])
    {
        $options = [
             'json'    => $parameters,
             'headers' => $headers,
        ];

        $trier = new CallbackTrier(function () use ($method, $uri, $options) {
            return $this->client->request($method, $uri, $options);
        }, $this->tries);

        $response = $trier->doTry();


        return json_decode($response->getBody()->getContents(), true);
    }



    public function setTries(int $tries)
    {
        $this->tries = $tries;
    }
}
