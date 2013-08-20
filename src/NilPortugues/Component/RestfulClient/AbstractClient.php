<?php
namespace NilPortugues\Component\RestfulClient;

abstract class AbstractClient
{
    /**
     *
     * @param  mixed $data
     * @param  array $responseHeaders
     * @return type
     */
    protected function prepareResponse($data,array &$responseHeaders)
    {
        if (!empty($responseHeaders['Content-Type'])) {
            $type = explode(';',$responseHeaders['Content-Type']);
            $type = trim(strtolower($type[0]));

            switch ($type) {
                case 'application/json':
                    return \NilPortugues\Component\RestfulClient\Responses\JSONResponse::convert($data);
                    break;

                case 'application/xml':
                    return \NilPortugues\Component\RestfulClient\Responses\XMLResponse::convert($data);
                    break;

                default:

                    //Check if is PHP serialized data
                    $unserialized = @unserialize($data);
                    if ($unserialized !== false) {
                        return $unserialized;
                    }
                    //Or return RAW data.
                    else {
                        return $data;
                    }
                    break;
            }
        } else {
            return $data;
        }
    }
}
