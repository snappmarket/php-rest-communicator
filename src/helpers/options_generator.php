<?php

use SnappMarket\Communicator\Communicator;

if (! function_exists('generate_options')) {
    /**
     * @param string $content_type
     * @param array $parameters
     * @return array|array[]
     */
    function generate_options(string $content_type, array $parameters)
    {
        switch ($content_type){
            case Communicator::APPLICATION_JSON:
                return ['json' => json_encode($parameters)];
            case Communicator::X_WWW_FORM_URLENCODED:
                return ['form_params' => $parameters];
            case Communicator::MULTIPART_FORM_DATA:
                return ['multipart' => $parameters];
            default:
                return ['json' => json_encode($parameters)];
        }
    }
}
