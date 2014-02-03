<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sonrisa\Component\RestfulClient;

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
                    $data = Responses\JSONResponse::convert($data);
                    $status = explode(' ',$data['headers']['Status']);
                    $data['headers']['Status'] = $status[0];
                    return $data;
                    break;

                case 'application/xml':
                    $data = Responses\XMLResponse::convert($data);
                    $status = explode(' ',$data['headers']['Status']);
                    $data['headers']['Status'] = $status[0];
                    return $data;
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
