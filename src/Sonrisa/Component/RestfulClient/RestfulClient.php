<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sonrisa\Component\RestfulClient;

use \Sonrisa\Component\RestfulClient\Interfaces\RestfulClientInterface as RestfulClientInterface;
use \Sonrisa\Component\RestfulClient\CURLClient as CURLClient;
use \Sonrisa\Component\RestfulClient\FileGetContentsClient as FileGetContentsClient;
use \Sonrisa\Component\RestfulClient\Exceptions\RestfulClientException as RestfulClientException;

class RestfulClient implements RestfulClientInterface
{
    /**
     * Holds the instance of the Client we'll be using to fire the requests.
     *
     * @var \Sonrisa\Component\RestfulClient\Interfaces\ClientInterface.
     */
    protected $client;

    /**
     * List of default HTTP headers that will be sent in each request.
     *
     * @var array
     */
    protected $headers = array('Content-Type' => 'text/html; charset=utf-8');

    /**
     * Holds the API field name and value. Will be injected in every request.
     *
     * @var array
     */
    protected $apiKey = array();

    /**
     * Picks which client should be used.
     *
     * @throws RestfulClientException
     */
    public function __construct()
    {
        if ( $this->isCurlAvailable() ) {
            $this->client = new CURLClient();
        } elseif ( $this->isFileGetContentsExternalAvailable() ) {
            $this->client = new FileGetContentsClient();
        } else {
            throw new RestfulClientException('Your server does not allow connections to external sources.');
        }

        //Set some default HTTP headers.
        $this->setAcceptLanguage();
        $this->setAcceptEncoding();
        $this->setUserAgent('Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.2 Safari/537.36');
    }

    /**
     * Checks if CURL is available.
     *
     * @return bool
     */
    protected function isCurlAvailable()
    {
        return function_exists('curl_version');
    }

    /**
     * Check if URL aware fopen wrappers are enabled.
     *
     * @return bool
     */
    protected function isFileGetContentsExternalAvailable()
    {
        if ( ini_get('allow_url_fopen') ) {
            return true;
        }

        return false;
    }

    /**
     * Sets the default response language.
     *
     * @param  string $lang
     * @throws Exceptions\RestfulClientException
     * @return RestfulClient
     */
    public function setAcceptLanguage($lang = 'en')
    {
        if ( 0 == strlen($lang) ) {
            throw new RestfulClientException('The language code cannot be empty or NULL');
        }

        $this->headers['Accept-language'] = $lang;

        return $this;
    }

    /**
     * Sets the HTTP header value Accept-Encoding. Default is gzip.
     *
     * @param  string        $value
     * @return RestfulClient
     */
    public function setAcceptEncoding($value = 'compress, gzip')
    {
        $this->headers['Accept-Encoding'] = $value;

        return $this;
    }

    /**
     * (Optional) Sets a User-Agent in every request.
     *
     * @param $agentString
     * @return RestfulClient
     */
    public function setUserAgent($agentString)
    {
        $this->headers['User-Agent'] = $agentString;

        return $this;
    }

    /**
     * (Optional) Sets a Basic Authorization in every request.
     *
     * @param $username
     * @param $password     
     * @return RestfulClient
     */
    public function setBasicAuthorization($username,$password)
    {
        $this->headers['Authorization'] = "Basic " . base64_encode("$username:$password");

        return $this;         
    }


    /**
     * (Optional) It will set the API's key or token value and will be send in every client request.
     *
     * @param  string                 $keyName
     * @param  string                 $value
     * @return RestfulClient
     */
    public function setKey($keyName,$value)
    {
        $this->apiKey = array_merge($this->apiKey,array($keyName => $value));

        return $this;
    }

    /**
     * Allows sending a request to the specified URL using HTTP's GET method.
     *
     * @param  string $url
     * @param  array  $params
     * @return mixed
     */
    public function get($url,array $params=array())
    {
        $params = array_merge($this->apiKey,$params);

        return $this->client->request('GET',$url,$params,$this->headers);
    }

    /**
     * Allows sending a request to the specified URL using HTTP's POST method.
     *
     * @param  string $url
     * @param  array  $params
     * @return mixed
     */
    public function post($url,array $params=array())
    {
        $params = array_merge($this->apiKey,$params);

        return $this->client->request('POST',$url,$params,$this->headers);
    }

    /**
     * Allows sending a request to the specified URL using HTTP's PUT method.
     *
     * @param  string $url
     * @param  array  $params
     * @return mixed
     */
    public function put($url,array $params=array())
    {
        $params = array_merge($this->apiKey,$params);

        return $this->client->request('PUT',$url,$params,$this->headers);
    }

    /**
     * Allows sending a request to the specified URL using HTTP's PATCH method.
     *
     * @param  string $url
     * @param  array  $params
     * @return mixed
     */
    public function patch($url,array $params=array())
    {
        $params = array_merge($this->apiKey,$params);

        return $this->client->request('PATCH',$url,$params,$this->headers);
    }

    /**
     * Allows sending a request to the specified URL using HTTP's DELETE method.
     *
     * @param  string $url
     * @param  array  $params
     * @return mixed
     */
    public function delete($url,array $params=array())
    {
        $params = array_merge($this->apiKey,$params);

        return $this->client->request('DELETE',$url,$params,$this->headers);
    }

    /**
     * Allows sending a request to the specified URL using HTTP's HEAD method.
     *
     * @param  string $url
     * @param  array  $params
     * @return mixed
     */
    public function options($url,array $params=array())
    {
        $params = array_merge($this->apiKey,$params);

        return $this->client->request('OPTIONS',$url,$params,$this->headers);
    }

    /**
     * Allows sending a request to the specified URL using HTTP's OPTIONS method.
     *
     * @param  string $url
     * @param  array  $params
     * @return mixed
     */
    public function head($url,array $params=array())
    {
        $params = array_merge($this->apiKey,$params);

        return $this->client->request('HEAD',$url,$params,$this->headers);
    }

    /**
     * Allows sending a request to the specified URL using a custom HTTP method.
     *
     * @param  string $methodName
     * @param  string $url
     * @param  array  $params
     * @return mixed
     */
    public function other($methodName,$url,array $params=array())
    {
        $params = array_merge($this->apiKey,$params);

        return $this->client->request($methodName,$url,$params,$this->headers);
    }
}
