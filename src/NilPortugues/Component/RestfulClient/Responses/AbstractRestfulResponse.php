<?php
namespace NilPortugues\Component\RestfulClient\Responses;

abstract class AbstractRestfulResponse
{
    /**
     *
     * @param  mixed                                                                  $response
     * @return array
     * @throws NilPortugues\Component\RestfulClient\Exceptions\RestfulClientException
     */
    abstract public static function convert($response);
}
