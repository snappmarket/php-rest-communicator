<?php

namespace SnappMarket\Communicator\SmRequest;

use GuzzleHttp\Client;
use ReflectionObject;
use SnappMarket\Exceptions\RequestMethodNotFoundException;

/**
 * Class RequestFactory
 * @package SnappMarket\Communicator\SmRequest
 */
class RequestFactory
{
    /**
     * @param string $method
     * @param Client $client
     * @return mixed
     * @throws RequestMethodNotFoundException
     */
    public static function make(string $method, Client $client)
    {
        $class_name = (new ReflectionObject(new self()))->getNamespaceName().'\\'.ucfirst(strtolower($method));
        if (class_exists($class_name)) {
            return new $class_name($client);
        } else {
            throw new RequestMethodNotFoundException();
        }
    }

}