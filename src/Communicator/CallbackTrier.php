<?php


namespace SnappMarket\Communicator;

use Closure;
use Exception;

class CallbackTrier
{
    /** @var Closure */
    protected $callback;

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
            $function = $this->callback;

            return $function();
        } catch (Exception $exception) {
            if ($tried < $this->tries) {
                $this->doTry($tried + 1);
            }

            throw $exception;
        }
    }
}
