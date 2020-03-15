<?php

namespace SnappMarket\Communicator;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

class Communicator implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    const DEFAULT_TRIES_VALUE = 1;

    const METHOD_POST = 'POST';
    const METHOD_GET  = 'GET';


    /** @var Client */
    protected $client;


    /** @var string */
    protected $baseUri;


    /** @var int */
    protected $tries = self::DEFAULT_TRIES_VALUE;



    public function __construct(string $baseUri, array $headers = [], ?LoggerInterface $logger = null)
    {
        $options = [
             'base_uri' => $baseUri,
             'headers'  => $headers,
        ];

        $this->baseUri = $baseUri;
        $this->client  = new Client($options);

        if ($logger) {
            $this->setLogger($logger);
        }
    }



    public function post(string $uri, array $parameters = [], array $headers = []): ResponseInterface
    {
        return $this->request(static::METHOD_POST, $uri, $parameters, $headers);
    }



    public function get(string $uri, array $parameters = [], array $headers = []): ResponseInterface
    {
        return $this->request(static::METHOD_GET, $uri, $parameters, $headers);
    }



    public function request(string $method, string $uri, array $parameters = [], array $headers = []): ResponseInterface
    {
        $options = [
             'json'    => $parameters,
             'headers' => $headers,
        ];


        $trier = new CallbackTrier(function () use ($method, $uri, $options) {
            $this->logRequest($method, $uri, $options);

            return $this->client->request($method, $uri, $options);
        }, $this->tries);


        $trier->setFallback(function (Exception $exception) use ($method, $uri, $options) {
            $this->logFailure($uri, $exception);
        });


        $response = $trier->doTry();
        $this->logResponse($uri, $response);


        return $response;
    }



    public function setTries(int $tries)
    {
        $this->tries = $tries;
    }



    protected function logRequest(string $method, string $uri, array $options = [])
    {
        $context = [
             'url'     => $this->baseUri . $uri,
             'method'  => $method,
             'options' => json_encode($options),
        ];

        $this->logger->info($this->getRequestLoggingTemplate(), $context);
    }



    protected function getRequestLoggingTemplate()
    {
        return 'Communication request to `{url}` is being sent with the `{method}` method: {options}';
    }



    protected function logFailure(string $uri, Exception $exception)
    {
        $context = [
             'url'       => $this->baseUri . $uri,
             'exception' => get_class($exception),
        ];

        $this->logger->error($this->getFailureLoggingTemplate(), $context);
    }



    protected function getFailureLoggingTemplate()
    {
        return 'Communication request to `{url}` has been failed with exception: {exception}';
    }



    protected function logResponse(string $uri, Response $response)
    {
        $context = [
             'url'  => $this->baseUri . $uri,
             'body' => $response->getBody()->__toString(),
        ];

        $this->logger->info($this->getResponseLoggingTemplate(), $context);
    }



    protected function getResponseLoggingTemplate()
    {
        return 'Communication response from `{url}` has been recieved: {body}';
    }
}
