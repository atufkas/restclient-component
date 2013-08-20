<?php
namespace NilPortugues\Component\RestfulClient\Responses;

use  NilPortugues\Component\RestfulClient\Responses\RestfulResponseInterface as RestfulResponseInterface;

class JSONResponse implements RestfulResponseInterface
{
    /**
     *
     * @param  string                                                                  $response
     * @return array
     * @throws \NilPortugues\Component\RestfulClient\Exceptions\RestfulClientException
     */
    public static function convert($response)
    {
        $array = json_decode($response,true);

        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                throw new \NilPortugues\Component\RestfulClient\Exceptions\RestfulClientException('JSON Response error: Maximum stack depth exceeded');
                break;

            case JSON_ERROR_STATE_MISMATCH:
                throw new \NilPortugues\Component\RestfulClient\Exceptions\RestfulClientException('JSON Response error: Underflow or the modes mismatch');
                break;

            case JSON_ERROR_CTRL_CHAR:
                throw new \NilPortugues\Component\RestfulClient\Exceptions\RestfulClientException('JSON Response error: Unexpected control character found');
                break;

            case JSON_ERROR_SYNTAX:
                throw new \NilPortugues\Component\RestfulClient\Exceptions\RestfulClientException('JSON Response error: Syntax error, malformed JSON');
                break;

            case JSON_ERROR_UTF8:
                throw new \NilPortugues\Component\RestfulClient\Exceptions\RestfulClientException('JSON Response error: Malformed UTF-8 characters, possibly incorrectly encoded');
                break;

        }

        return $array;
    }
}
