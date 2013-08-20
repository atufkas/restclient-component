<?php
namespace NilPortugues\Component\RestfulClient\Responses;

interface RestfulResponseInterface
{
    /**
     *
     * @param  mixed                                                                  $response
     * @return array
     * @throws NilPortugues\Component\RestfulClient\Exceptions\RestfulClientException
     */
     public static function convert($response);
}
