<?php


namespace SnappMarket\Communicator\SmRequest;


use Psr\Http\Message\ResponseInterface;

/**
 * Interface SmRequestInterface
 * @package SnappMarket\Communicator\SmRequest
 */
interface SmRequestInterface
{
    /**
     * @param string $uri
     * @param array $parameters
     * @return mixed
     */
    public function execute(string $uri, array $parameters = [], array $headers = []): ResponseInterface;
}