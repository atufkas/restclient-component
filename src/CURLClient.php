<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sonrisa\Component\RestfulClient;

use Sonrisa\Component\RestfulClient\Exceptions\RestfulClientException;
use Sonrisa\Component\RestfulClient\Interfaces\ClientInterface;

class CURLClient extends AbstractClient implements ClientInterface
{
    protected $curl;

    /**
     * Sets up CURL.
     */
    public function __construct()
    {
        //Set CURL basic fields.
        $this->curl = curl_init();

        curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($this->curl, CURLOPT_TIMEOUT, 30);            // Fail if takes longer than 30 seconds.
        curl_setopt($this->curl, CURLOPT_FAILONERROR, false);     // Do not fail on 40x codes.
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);   // Follow redirects
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($this->curl, CURLOPT_HEADER, true);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);   // return transfer instead of outputting .
        curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
    }

    /**
     * Set any arbitrary CURL option via curl_setopt.
     * @param $curlOptName
     * @param $curlOptValue
     */
    public function setOpt($curlOptName, $curlOptValue)
    {
        return curl_setopt($this->curl, $curlOptName, $curlOptValue);
    }

    /**
     * Set timeout for request.
     * @return ClientInterface
     */
    public function setTimeout($timeout)
    {
        curl_setopt($this->curl, CURLOPT_TIMEOUT, $timeout);
        return $this;
    }

    /**
     * @param  string $methodName
     * @param  string $url
     * @param  array $params
     * @param  array $headers
     * @throws Exceptions\RestfulClientException
     * @return mixed
     */
    public function request($methodName, $url, array $params=array(), array $headers=array())
    {
        if (filter_var($url, FILTER_VALIDATE_URL, array('options' => array('flags' => FILTER_FLAG_PATH_REQUIRED)))) {

            //Prepare the request.
            if ($methodName == 'GET') {
                $url = $url . "?" . http_build_query($params);
                curl_setopt($this->curl, CURLOPT_URL, $url);
            } else {

                $contentType = null;

                if (isset($headers[ 'Content-Type' ])) {
                    $ctParts = explode(';', $headers[ 'Content-Type' ]);
                    $contentType = trim($ctParts[ 0 ]);
                }

                switch ($contentType) {
                    case 'application/x-www-form-urlencoded':
                    default:
                        $postContent = http_build_query($params);
                        break;
                    case 'application/json':
                        $postContent = json_encode($params);
                        break;
                }

                curl_setopt($this->curl, CURLOPT_URL, $url);
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postContent);
                // Always set method as passed in $methodName - so we don't need shortcuts like CURLOPT_POST
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $methodName);

                if (! in_array($methodName, array('GET', 'POST'))) {
                    // As default as X-HHTP-Method-Overide header for http verbs other than GET and POST
                    // so that APIs behind a permissive firewall have a chance to detect request method:
                    curl_setopt($this->curl, CURLOPT_HTTPHEADER, array("X-HTTP-Method-Override: {$methodName}"));
                }
            }

            //Prepare headers and related config.
            if (!empty($headers['User-Agent'])) {
                curl_setopt($this->curl, CURLOPT_USERAGENT, $headers['User-Agent']);
                unset($headers['User-Agent']);
            }

            if (!empty($headers['Accept-Encoding'])) {
                curl_setopt($this->curl, CURLOPT_ENCODING, $headers['Accept-Encoding']);
                unset($headers['Accept-Encoding']);
            }

            // Prepare and set remaining header directly:
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array_map(function ($key, $value) {
                return $key . ': ' . $value;
            }, array_keys($headers), $headers));

            //Send request and retrieve the response.
            $data = curl_exec($this->curl);

            // Turn any kind of curl errors into exceptions
            if (curl_errno($this->curl)) {
                throw new RestfulClientException(sprintf('curl error %s: "%s"',
                    curl_errno($this->curl), curl_error($this->curl)));
            }

            $data = explode("\r\n", $data);

            //Reads the response HTTP header and returns its contents in an array.
            $response = array(
                'Protocol'  => ((trim(substr($data[0], 0, 8))))? trim(substr($data[0], 0, 8)) : null,
                'Status'    => curl_getinfo($this->curl, CURLINFO_HTTP_CODE),
            );
            unset($data[0]);
            $info = array_pop($data);

            foreach ($data as $value) {
                $k = substr($value, 0, strpos($value, ':'));
                $v = trim(substr(strstr($value, ':'), 1));

                if (!empty($k) && !empty($v)) {
                    $k = str_replace(' ', '-', ucwords(str_replace('-', ' ', $k)));
                    $response[$k] = $v;
                }
            }
            $response['Request-Time'] = curl_getinfo($this->curl, CURLINFO_TOTAL_TIME).' seconds';
            array_filter($response);

            //Return data.
            return array(
                'request' => array_merge(array('URL'=>$url), $this->getRequestHeaders()),
                'response' => $this->prepareResponse($info, $response),
                'headers' => $response
            );
        } else {
            throw new \Sonrisa\Component\RestfulClient\Exceptions\RestfulClientException("The provided URL: '{$url}', is not valid.");
        }
    }

    /**
     * Reads the requested HTTP header and returns it in array form.
     *
     * @return array
     */
    protected function getRequestHeaders()
    {
        $requested = curl_getinfo($this->curl, CURLINFO_HEADER_OUT);
        $requested = explode("\r\n", $requested);

        $request = array();
        foreach ($requested as $value) {
            $k = substr($value, 0, strpos($value, ':'));
            $v = trim(substr(strstr($value, ':'), 1));

            if (!empty($k) && !empty($v)) {
                $k = str_replace(' ', '-', ucwords(str_replace('-', ' ', $k)));
                $request[$k] = $v;
            }
        }

        return $request;
    }

    /**
     * Destroys CURL.
     */
    public function __destruct()
    {
        curl_close($this->curl);
    }
}
