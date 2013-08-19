<?php
namespace NilPortugues\Component\RestfulClient\Interfaces;

interface RestfulClientInterface
{
    /**
     * Sets the default response language.
     *
     * @param  string                                                                  $lang
     * @return \NilPortugues\Component\RestfulClient\Interfaces\RestfulClientInterface
     */
    public function setAcceptLanguage($lang = 'en');

    /**
     * Sets the HTTP header value Accept-Encoding. Default is gzip.
     *
     * @param  string                                                                  $value
     * @return \NilPortugues\Component\RestfulClient\Interfaces\RestfulClientInterface
     */
    public function setAcceptEncoding($value = 'gzip');

    /**
     * (Optional) Sets a User-Agent in every request.
     *
     * @param $agentString
     * @return \NilPortugues\Component\RestfulClient\Interfaces\RestfulClientInterface
     */
    public function setUserAgent($agentString);

    /**
     * (Optional) It will set the API's key or token value and will be send in every client request.
     *
     * @param  string                                                                  $keyName
     * @param  string                                                                  $value
     * @return \NilPortugues\Component\RestfulClient\Interfaces\RestfulClientInterface
     */
    public function setKey($keyName,$value);

    /**
     * Allows sending a request to the specified URL using HTTP's GET method.
     *
     * @param  string $url
     * @param  array  $params
     * @return mixed
     */
    public function get($url,array $params=array());

    /**
     * Allows sending a request to the specified URL using HTTP's POST method.
     *
     * @param  string $url
     * @param  array  $params
     * @return mixed
     */
    public function post($url,array $params=array());

    /**
     * Allows sending a request to the specified URL using HTTP's PUT method.
     *
     * @param  string $url
     * @param  array  $params
     * @return mixed
     */
    public function put($url,array $params=array());

    /**
     * Allows sending a request to the specified URL using HTTP's PATCH method.
     *
     * @param  string $url
     * @param  array  $params
     * @return mixed
     */
    public function patch($url,array $params=array());

    /**
     * Allows sending a request to the specified URL using HTTP's DELETE method.
     *
     * @param  string $url
     * @param  array  $params
     * @return mixed
     */
    public function delete($url,array $params=array());

    /**
     * Allows sending a request to the specified URL using HTTP's HEAD method.
     *
     * @param  string $url
     * @param  array  $params
     * @return mixed
     */
    public function options($url,array $params=array());

    /**
     * Allows sending a request to the specified URL using HTTP's OPTIONS method.
     *
     * @param  string $url
     * @param  array  $params
     * @return mixed
     */
    public function head($url,array $params=array());

    /**
     * Allows sending a request to the specified URL using a custom HTTP method.
     *
     * @param  string $methodName
     * @param  string $url
     * @param  array  $params
     * @return mixed
     */
    public function other($methodName,$url,array $params=array());
}
