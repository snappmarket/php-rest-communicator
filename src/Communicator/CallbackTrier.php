<?php


namespace SnappMarket\Communicator;

use Closure;
use Exception;

/**
 * Class CallbackTrier
 * @package SnappMarket\Communicator
 */
class CallbackTrier
{
    /** @var Closure */
    protected $callback;


    /** @var Closure|null */
    protected $fallback;

    /** @var int */
    protected $tries;



    /**
     * CallbackTrier constructor.
     *
     * @param Closure $callback
     * @param int     $tries
     */
    public function __construct(Closure $callback, int $tries)
    {
        $this->callback = $callback;
        $this->tries    = $tries;
    }



    /**
     * @param Closure $fallback
     */
    public function setFallback(Closure $fallback)
    {
        $this->fallback = $fallback;
    }



    /**
     * Does the try for the $callback property in a number of $tries property.
     *
     * @param int $tried
     *
     * @return mixed
     * @throws Exception
     */
    public function doTry(int $tried = 1)
    {
        try {
            $callback = $this->callback;

            return $callback();
        } catch (Exception $exception) {
            if ($this->fallback) {
                $fallback = $this->fallback;
                $fallback($exception);
            }

            if ($tried < $this->tries) {
                return $this->doTry($tried + 1);
            }

            throw $exception;
        }
    }
}
