[![Build Status](https://travis-ci.org/sonrisa/restclient-component.svg)](https://travis-ci.org/sonrisa/restclient-component) [![Coverage Status](https://coveralls.io/repos/sonrisa/restclient-component/badge.svg?branch=master)](https://coveralls.io/r/sonrisa/restclient-component?branch=master)  [![SensioLabsInsight](https://insight.sensiolabs.com/projects/4fcf3911-400b-4818-885f-ebefb44c82a8/mini.png)](https://insight.sensiolabs.com/projects/4fcf3911-400b-4818-885f-ebefb44c82a8) [![Latest Stable Version](https://poser.pugx.org/sonrisa/restclient-component/v/stable)](https://packagist.org/packages/sonrisa/restclient-component) [![Total Downloads](https://poser.pugx.org/sonrisa/restclient-component/downloads)](https://packagist.org/packages/sonrisa/restclient-component) [![License](https://poser.pugx.org/sonrisa/restclient-component/license)](https://packagist.org/packages/sonrisa/restclient-component) 

# Restful Client Component

The Restful Client Component allows communication with external APIs using an easy interface. 

Uses CURL to fire the requests. If CURL is not available, it will try to fall back using file_get_contents instead.

* [1.Installation](#block1)
* [2. Methods available](#block2)
* [3. Usage](#block3)
    * [3.1 - Client Request](#block3.1)
    * [3.2 - Client Response](#block3.2)
* [4. To do](#block4)
* [5. Author](#block5)

<a name="block1"></a>
## 1. Installation

Add the following to your `composer.json` file :

```js
"sonrisa/restclient-component":"dev-master"
```
<a name="block2"></a>
## 2. Methods available

- $this->setHeader($field,$value);
- $this->setHeaders($headers);
- $this->setAcceptLanguage($lang = 'en');
- $this->setAcceptEncoding($value = 'gzip');
- $this->setUserAgent($agentString);
- $this->setBasicAuthorization($username,$password);
- $this->setKey($keyName,$value);
- $this->get($url,array $params=array());
- $this->post($url,array $params=array());
- $this->put($url,array $params=array());
- $this->patch($url,array $params=array());
- $this->delete($url,array $params=array());
- $this->options($url,array $params=array());
- $this->head($url,array $params=array());
- $this->other($methodName,$url,array $params=array());

<a name="block3"></a>
## 3. Usage
Usage is really straight-forward. Example provided below.

<a name="block3.1"></a>
### 3.1 - Client Request
```php
<?php

use \Sonrisa\Component\RestfulClient\RestfulClient;

$url = 'http://api.duckduckgo.com/?q=DuckDuckGo&format=json&pretty=1';

$client = new RestfulClient();

// (Optional) Directly set a header field
$client->setHeader('Content-Type', 'application/json');

// (Optional) Set some headers with convenient functions
$client->setAcceptLanguage('ca,en;q=0.8,es;q=0.6')
       ->setAcceptEncoding('gzip,deflate,sdch')
       ->setUserAgent('Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.2 Safari/537.36');

// (Optional) Add the API key and the username for each request
// Will result in: http://api.duckduckgo.com/?q=DuckDuckGo&format=json&pretty=1&api=$apiKey&username=$username
$client->setKey('api',$apiKey);
$client->setKey('username',$username);

// Sending parameters is optional,
// so $params is actually an optional argument.
$params = array();

// Send a GET Request:
$response = $client->get($url,$params);

// Send a POST Request
$response = $client->post($url ,$params);

// Send a PUT Request
$response = $client->put($url ,$params);

// Send a PATCH Request
$response = $client->patch($url ,$params);

// Send a DELETE Request
$response = $client->delete($url ,$params);

// Send a HEAD Request
$response = $client->head( $url ,$params);

// Send a OPTIONS Request
$response = $client->options($url ,$params);

// Send a CUSTOM Request
$response = $client->other('X-SonrisaCMS-Header', $url ,$params);
```
<a name="block3.2"></a>
### 3.2 - Client Response
Response is always returned an key-value array, where keys names are the names of the headers returned in the response.

The array will always contain 3 main keys:

 - `$response['request']` : holding the request HTTP Headers build by the class
 - `$response['response']` : holding all data returned by the queried URL
 - `$response['headers']` : holding the response's HTTP Headers.

Response example:
```php
<?php

//var_dump($response);
array(3) {
  'request' =>
  array(3) {
    'URL' =>
    string(68) "http://api.duckduckgo.com/?q=DuckDuckGo&format=json&pretty=1?count=2"
    'Host' =>
    string(18) "api.duckduckgo.com"
    'Accept' =>
    string(3) "*/*"
  }
  'response' =>
  string(3807) "{
   "Definition" : "",
   "DefinitionSource" : "",
   "Heading" : "DuckDuckGo",
   "AbstractSource" : "Wikipedia",
   "Image" : "https://i.duckduckgo.com/i/d9dea591.png",
   "RelatedTopics" : [
      {
         "Result" : "<a href=\"http://duckduckgo.com/Duck%2C_duck%2C_goose\">Duck, duck, goose</a> - Duck, Duck, Goose or Duck, Duck, Gray Duck is a traditional children's game often first learned in pre-school or kindergarten  then later adapted on the playground for early elementary stu...",
         "Icon"...
  'headers' =>
  array(13) {
    'Protocol' =>
    string(8) "HTTP/1.1"
    'Status' =>
    int(200)
    'Server' =>
    string(5) "nginx"
    'Date' =>
    string(29) "Mon, 03 Feb 2014 20:14:52 GMT"
    'Content-Type' =>
    string(39) "application/x-javascript; charset=UTF-8"
    'Transfer-Encoding' =>
    string(7) "chunked"
    'Connection' =>
    string(10) "keep-alive"
    'X-DuckDuckGo-Results' =>
    string(1) "1"
    'Expires' =>
    string(29) "Mon, 03 Feb 2014 20:14:53 GMT"
    'Cache-Control' =>
    string(9) "max-age=1"
    'Strict-Transport-Security' =>
    string(9) "max-age=0"
    'X-DuckDuckGo-Locale' =>
    string(5) "en_US"
    'Request-Time' =>
    string(16) "0.237205 seconds"
  }
}
```

<a name="block4"></a>
## 4. To do

### Better methods
- For file get contents, follow 301 and 302 codes and throw request again at the returned URL.

### Better testing
- Create a better test case using [PHP as a server](http://www.php.net/manual/en/features.commandline.webserver.php) waiting for requests and returning responses.
- Testing for multipart/data both for curl and file_get_contents based client classes.

<a name="block5"></a>
## 5. Author
Nil Portugués Calderó
 - <contact@nilportugues.com>
 - http://nilportugues.com
