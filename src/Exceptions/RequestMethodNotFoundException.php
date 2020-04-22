<?php


namespace SnappMarket\Exceptions;


use Exception;
use Throwable;

/**
 * Class MethodNotFoundException
 * @package SnappMarket\Exceptions
 */
class RequestMethodNotFoundException extends Exception
{
    /**
     * Construct the exception. Note: The message is NOT binary safe.
     * @link https://php.net/manual/en/exception.construct.php
     * @param string $message [optional] The Exception message to throw.
     * @param int $code [optional] The Exception code.
     * @param Throwable $previous [optional] The previous throwable used for the exception chaining.
     */
    public function __construct($message = "Request Method Not Found Exception.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}