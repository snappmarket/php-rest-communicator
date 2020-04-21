<?php


namespace SnappMarket\Communicator\SmRequest;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use SnappMarket\Communicator\Communicator;

/**
 * Class Get
 * @package SnappMarket\Communicator\SmRequest
 */
class Get implements SmRequestInterface
{
    protected $client;

    /**
     * Post constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $uri
     * @param array $parameters
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function execute(string $uri, array $parameters = []): ResponseInterface
    {
        $options = [
            'query' => $parameters
        ];
        return $this->client->request(Communicator::METHOD_GET, $uri, $options);
    }
}