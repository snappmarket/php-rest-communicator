<?php


namespace SnappMarket\Communicator;

/**
 * Trait OptionsGenerator
 * @package SnappMarket\Communicator\SmRequest
 */
trait OptionsGenerator
{
    /**
     * @param string $content_type
     * @param array $parameters
     * @return array
     */
    public function generateOptions(string $content_type, array $parameters)
    {
        switch ($content_type){
            case Communicator::APPLICATION_JSON:
                return ['json' => $parameters];
            case Communicator::X_WWW_FORM_URLENCODED:
                return ['form_params' => $parameters];
            case Communicator::MULTIPART_FORM_DATA:
                return ['multipart' => $parameters];
            default:
                return ['json' => $parameters];
        }
    }
}