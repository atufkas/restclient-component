<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Component\RestfulClient;

use NilPortugues\Component\RestfulClient\Interfaces\ClientInterface as ClientInterface;

class FileGetContentsClient extends AbstractClient implements ClientInterface
{
    /**
     * @param  string                            $methodName
     * @param  string                            $url
     * @param  array                             $params
     * @param  array                             $headers
     * @return array|mixed
     * @throws Exceptions\RestfulClientException
     */
    public function request($methodName,$url,array $params=array(),array $headers=array())
    {
        if ( strlen($url)>0 && filter_var( $url, FILTER_VALIDATE_URL, array('options' => array('flags' => FILTER_FLAG_PATH_REQUIRED)) ) ) {

            $requestData = array
            (
                'Host' => parse_url($url,PHP_URL_HOST),
                'URL' => $url,
            );

            if (!empty($headers['User-Agent'])) {
                $requestData['User-Agent'] = $headers['User-Agent'];
            }

            if (!empty($headers['Accept-Encoding'])) {
                $requestData['Accept-Encoding'] = $headers['Accept-Encoding'];
            }

            //Prepare the request.
            if ($methodName == 'GET') {
                $url = $url . "?" . http_build_query($params);
                $request = array
                (
                    'ignore_errors' => true,
                    'method'    => $methodName,
                    'header'    => $this->buildHeader($headers),
                );
            } else {
                //This is default value if no one actually set it up.
                if (empty($headers['Content-Type'])) {
                    $headers['Content-Type'] = 'application/x-www-form-urlencoded';
                }

                $request = array
                (
                    'ignore_errors' => true,
                    'content'   => http_build_query($params),
                    'method'    => $methodName,
                    'header'    => $this->buildHeader($headers),
                );
            }
            //Remove any possible empty fields.
            array_filter($request);

            //Send request and retrieve the response.
            $timeStart = microtime(true);
            $data = file_get_contents($url, false, stream_context_create(array('http' => $request)));

            //Reads the response HTTP header and returns its contents in an array.
            $response = array
            (
                'Protocol' => trim(substr($http_response_header[0],0,8)),
                'Status' => trim(substr($http_response_header[0],8)),
            );
            foreach ($http_response_header as $value) {
                $k = substr($value,0,strpos($value, ':'));
                $v = trim(substr(strstr($value, ':'),1));

                if ( !empty($k) && !empty($v) ) {
                    $k = str_replace(' ','-',ucwords(str_replace('-',' ',$k)));
                    $response[$k] = $v;
                }
            }
            $response['Request-Time'] = microtime(true) - $timeStart. ' seconds';

            //Return data.
            return array
            (
                'request' => $requestData,
                'response' => $this->prepareResponse($data,$response),
                'headers' => $response
            );

        } else {
            throw new \NilPortugues\Component\RestfulClient\Exceptions\RestfulClientException("The provided URL: '{$url}', is not valid.");
        }
    }

    /**
     * Builds the headers that will be used in the request.
     *
     * @param  array       $headers
     * @return null|string
     */
    protected function buildHeader(array $headers)
    {
        $headersString = '';

        //Build the headers string.
        if (!empty($headers)) {

            foreach ($headers as $key => $value) {
                $headersString.= "{$key}: {$value}\r\n";
            }
        }

        return (strlen($headersString)>0)? $headersString : NULL;
    }

}
