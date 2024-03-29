<?php


namespace SnappMarket\Communicator\SmRequest;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use SnappMarket\Communicator\Communicator;
use SnappMarket\Communicator\OptionsGenerator;

/**
 * Class Delete
 * @package SnappMarket\Communicator\SmRequest
 */
class Delete implements SmRequestInterface
{
    use OptionsGenerator;

    protected $client;

    /**
     * Delete constructor.
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
    public function execute(string $uri, array $parameters = [], array $headers = []): ResponseInterface
    {
        $content_type = $headers['Content-Type'] ?? Communicator::X_WWW_FORM_URLENCODED;

        $options = $this->generateOptions($content_type,$parameters);
        return $this->client->request(Communicator::METHOD_DELETE, $uri, $options);
    }
}
