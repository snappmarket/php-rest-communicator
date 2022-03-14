<?php


namespace SnappMarket\Communicator\SmRequest;


use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use SnappMarket\Communicator\Communicator;
use SnappMarket\Communicator\OptionsGenerator;

class Patch implements SmRequestInterface
{
    use OptionsGenerator;

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
    public function execute(string $uri, array $parameters = [], array $headers = []): ResponseInterface
    {
        $content_type = $headers['Content-Type'] ?? Communicator::X_WWW_FORM_URLENCODED;

        $options = $this->generateOptions($content_type, $parameters);
        return $this->client->request(Communicator::METHOD_PATCH, $uri, $options);
    }
}
