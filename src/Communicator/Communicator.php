<?php

namespace SnappMarket\Communicator;

use GuzzleHttp\Client;

class Communicator
{
    const DEFAULT_TRIES_VALUE = 1;

    protected $client;


    protected $token;


    protected $tries;



    public function __construct(string $baseUrl, string $token, int $tries = self::DEFAULT_TRIES_VALUE)
    {
        $options = [
             'base_uri' => $baseUrl,
        ];

        $this->client = new Client($options);

        $this->token = $token;
        $this->tries = $tries;
    }
}
