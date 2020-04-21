<?php


namespace SnappMarket\Communicator\SmRequest;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use SnappMarket\Communicator\Communicator;

/**
 * Class Post
 * @package SnappMarket\Communicator\SmRequest
 */
class Post implements SmRequestInterface
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
        $content_type = $headers['content-type'] ?? Communicator::APPLICATION_JSON;

        $options = generate_options($content_type,$parameters);
        return $this->client->request(Communicator::METHOD_POST, $uri, $options);
    }
}