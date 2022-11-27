<?php

namespace SnappMarket\Communicator;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use SnappMarket\Communicator\SmRequest\RequestFactory;

/**
 * Class Communicator
 * @package SnappMarket\Communicator
 */
class Communicator implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public const DEFAULT_TRIES_VALUE     = 1;
    public const METHOD_POST             = 'POST';
    public const METHOD_GET              = 'GET';
    public const METHOD_PUT              = 'PUT';
    public const METHOD_UPDATE           = 'UPDATE';
    public const METHOD_DELETE           = 'DELETE';
    public const METHOD_PATCH            = 'PATCH';
    public const APPLICATION_JSON        = 'application/json';
    public const MULTIPART_FORM_DATA     = 'multipart/form-data';
    public const X_WWW_FORM_URLENCODED   = 'application/x-www-form-urlencoded';


    /** @var Client */
    protected $client;


    /** @var string */
    protected $baseUri;


    /** @var int */
    protected $tries = self::DEFAULT_TRIES_VALUE;


    /**
     * Communicator constructor.
     * @param string $baseUri
     * @param array $headers
     * @param LoggerInterface|null $logger
     */
    public function __construct(string $baseUri, array $headers = [], ?LoggerInterface $logger = null, $extraOptions = [])
    {
        $options = [
             'base_uri' => $baseUri,
             'headers'  => $headers,
        ];
        if (!empty($extraOptions)) {
            $options = array_merge($options, $extraOptions);
        }

        $this->baseUri = $baseUri;
        $this->client  = new Client($options);

        if ($logger) {
            $this->setLogger($logger);
        }
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $parameters
     * @param array $headers
     * @return ResponseInterface
     * @throws Exception
     */
    public function request(string $method, string $uri, array $parameters = [], array $headers = []): ResponseInterface
    {

        $trier = new CallbackTrier(function () use ($method, $uri, $parameters, $headers) {
            $this->logRequest($method, $uri, ['parameters'=>$parameters,'headers'=>$headers]);
            return RequestFactory::make($method, $this->client)->execute($uri,$parameters, $headers);
        }, $this->tries);


        $trier->setFallback(function (Exception $exception) use ($uri) {
            $this->logFailure($uri, $exception);
        });


        $response = $trier->doTry();
        $this->logResponse($uri, $response);


        return $response;
    }


    /**
     * @param int $tries
     */
    public function setTries(int $tries)
    {
        $this->tries = $tries;
    }


    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     */
    protected function logRequest(string $method, string $uri, array $options = [])
    {
        $context = [
             'url'     => $this->baseUri . $uri,
             'method'  => $method,
             'options' => json_encode($options),
        ];

        $this->logger->info($this->getRequestLoggingTemplate(), $context);
    }


    /**
     * @return string
     */
    protected function getRequestLoggingTemplate()
    {
        return 'Communication request to `{url}` is being sent with the `{method}` method: {options}';
    }


    /**
     * @param string $uri
     * @param Exception $exception
     */
    protected function logFailure(string $uri, Exception $exception)
    {
        $context = [
             'url'       => $this->baseUri . $uri,
             'exception' => get_class($exception),
        ];

        $this->logger->error($this->getFailureLoggingTemplate(), $context);
    }


    /**
     * @return string
     */
    protected function getFailureLoggingTemplate()
    {
        return 'Communication request to `{url}` has been failed with exception: {exception}';
    }


    /**
     * @param string $uri
     * @param Response $response
     */
    protected function logResponse(string $uri, Response $response)
    {
        $context = [
             'url'  => $this->baseUri . $uri,
             'body' => $response->getBody()->__toString(),
        ];

        $this->logger->info($this->getResponseLoggingTemplate(), $context);
    }


    /**
     * @return string
     */
    protected function getResponseLoggingTemplate()
    {
        return 'Communication response from `{url}` has been received: {body}';
    }
}
