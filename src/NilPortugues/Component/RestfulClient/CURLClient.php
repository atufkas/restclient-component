<?php
namespace NilPortugues\Component\RestfulClient;

use NilPortugues\Component\RestfulClient\Interfaces\ClientInterface as ClientInterface;

class CURLClient implements ClientInterface
{
    protected $curl;

    /**
     * Sets up CURL.
     */
    public function __construct()
    {
        //Set CURL basic fields.
        $this->curl = curl_init();

        $cookie = tempnam ("/tmp", "CURLCOOKIE");
        curl_setopt($this->curl,CURLOPT_COOKIEJAR,$cookie);    //Allows cookies.
        curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($this->curl,CURLOPT_FOLLOWLOCATION,true);

        curl_setopt($this->curl,CURLOPT_TIMEOUT,30);            // Fail if takes longer than 30 seconds.
        curl_setopt($this->curl,CURLOPT_FAILONERROR,false);     // Do not fail on 40x codes.
        curl_setopt($this->curl,CURLOPT_FOLLOWLOCATION,true);   // Follow redirects
        curl_setopt($this->curl,CURLOPT_AUTOREFERER,true);
        curl_setopt($this->curl,CURLOPT_HEADER, true);
        curl_setopt($this->curl,CURLOPT_RETURNTRANSFER,true);   // return transfer instead of outputting .
        curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
    }

    /**
     * @param  string $methodName
     * @param  string $url
     * @param  array  $params
     * @param  array  $headers
     * @return mixed
     */
    public function request($methodName,$url,array $params=array(),array $headers=array())
    {
        if ( filter_var( $url, FILTER_VALIDATE_URL, array('options' => array('flags' => FILTER_FLAG_PATH_REQUIRED)) ) ) {

            //Prepare the request.
            if ($methodName == 'GET') {
                $url = $url . "?" . http_build_query($params);
                curl_setopt($this->curl, CURLOPT_URL, $url);
            } elseif ($methodName == 'POST') {
                curl_setopt($this->curl,CURLOPT_URL, $url);
                curl_setopt($this->curl,CURLOPT_POSTFIELDS, http_build_query($params));
                curl_setopt($this->curl,CURLOPT_POST,true);
            } elseif ($methodName == 'PUT') {
                curl_setopt($this->curl, CURLOPT_URL, $url);
            } else {
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $methodName);
                curl_setopt($this->curl, CURLOPT_HTTPHEADER, array("X-HTTP-Method-Override: {$methodName}"));
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
            curl_setopt($this->curl,CURLOPT_HTTPHEADER,$headers);    //Sets the remaining headers.

            //Send request and retrieve the response.
            $data = curl_exec($this->curl);
            $data = explode("\r\n",$data);

            //Reads the response HTTP header and returns its contents in an array.
            $response = array
            (
                'Protocol'  => ( (trim(substr($data[0],0,8))))? trim(substr($data[0],0,8)) : NULL,
                'Status'    => curl_getinfo($this->curl,CURLINFO_HTTP_CODE),
            );
            unset($data[0]);
            $info = array_pop($data);

            foreach ($data as $value) {
               $k = substr($value,0,strpos($value, ':'));
               $v = trim(substr(strstr($value, ':'),1));

               if ( !empty($k) && !empty($v) ) {
                   $k = str_replace(' ','-',ucwords(str_replace('-',' ',$k)));
                   $response[$k] = $v;
               }
            }
            $response['Request-Time'] = curl_getinfo($this->curl,CURLINFO_TOTAL_TIME).' seconds';
            array_filter($response);

            //Return data.
            return array('request' => $this->getRequestHeaders(),'response' => $info, 'headers' => $response );

        } else {
            throw new \NilPortugues\Component\RestfulClient\Exceptions\RestfulClientException("The provided URL: '{$url}', is not valid.");
        }
    }

    /**
     * Reads the requested HTTP header and returns it in array form.
     *
     * @return array
     */
    protected function getRequestHeaders()
    {
        //R
        $requested = curl_getinfo($this->curl,CURLINFO_HEADER_OUT);
        $requested = explode("\r\n",$requested);

        $request = array();
        foreach ($requested as $value) {
            $k = substr($value,0,strpos($value, ':'));
            $v = trim(substr(strstr($value, ':'),1));

            if ( !empty($k) && !empty($v) ) {
                $k = str_replace(' ','-',ucwords(str_replace('-',' ',$k)));
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
        curl_close ($this->curl);
    }
}
