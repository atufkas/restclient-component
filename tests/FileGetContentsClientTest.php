<?php
namespace Sonrisa\Test\Component\RestfulClient;

/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


class FileGetContentsClientTest extends \PHPUnit_Framework_TestCase
{
    protected $client;
    protected $params;
    protected $headers;

    public function setUp()
    {
        $this->client = new \Sonrisa\Component\RestfulClient\FileGetContentsClient();

        $this->params = array();
        $this->headers = array();
    }

    public function testInvalidURLRequest()
    {
        $methodName = 'GET';
        $url = '';
        $params = $this->params;
        $headers = $this->headers;

        $this->setExpectedException('\Sonrisa\Component\RestfulClient\Exceptions\RestfulClientException');
        $this->client->request($methodName, $url, $params, $headers);
    }

    public function testValidGETRequest()
    {
        $methodName = 'GET';
        $url = 'http://api.duckduckgo.com/?q=DuckDuckGo&format=json&pretty=1';
        $params = array('count'=>2 );
        $headers = $this->headers;

        $response = $this->client->request($methodName, $url, $params, $headers);

        $this->assertArrayHasKey('Protocol', $response['headers']);
        $this->assertArrayHasKey('Status', $response['headers']);
    }

    public function testValidPOSTRequest()
    {
        $methodName = 'POST';
        $url = 'http://api.duckduckgo.com/?q=DuckDuckGo&format=json&pretty=1';
        $params = array( 'track' => 'twitter' );
        $headers = $this->headers;

        $response = $this->client->request($methodName, $url, $params, $headers);

        $this->assertArrayHasKey('Protocol', $response['headers']);
        $this->assertArrayHasKey('Status', $response['headers']);
    }

    public function testValidPUTRequest()
    {
        $methodName = 'PUT';
        $url = 'http://api.duckduckgo.com/?q=DuckDuckGo&format=json&pretty=1';
        $params = array( 'track' => 'twitter' );
        $headers = $this->headers;

        $response = $this->client->request($methodName, $url, $params, $headers);

        $this->assertArrayHasKey('Protocol', $response['headers']);
        $this->assertArrayHasKey('Status', $response['headers']);
    }

    public function testValidPATCHRequest()
    {
        $methodName = 'PATCH';
        $url = 'http://api.duckduckgo.com/?q=DuckDuckGo&format=json&pretty=1';
        $params = array( 'track' => 'twitter' );
        $headers = $this->headers;

        $response = $this->client->request($methodName, $url, $params, $headers);

        $this->assertArrayHasKey('Protocol', $response['headers']);
        $this->assertArrayHasKey('Status', $response['headers']);
    }

    public function testValidDELETERequest()
    {
        $methodName = 'DELETE';
        $url = 'http://api.duckduckgo.com/?q=DuckDuckGo&format=json&pretty=1';
        $params = array( 'track' => 'twitter' );
        $headers = $this->headers;

        $response = $this->client->request($methodName, $url, $params, $headers);

        $this->assertArrayHasKey('Protocol', $response['headers']);
        $this->assertArrayHasKey('Status', $response['headers']);
    }

    public function testValidHEADRequest()
    {
        $methodName = 'HEAD';
        $url = 'http://api.duckduckgo.com/?q=DuckDuckGo&format=json&pretty=1';
        $params = array( 'track' => 'twitter' );
        $headers = $this->headers;

        $response = $this->client->request($methodName, $url, $params, $headers);

        $this->assertArrayHasKey('Protocol', $response['headers']);
        $this->assertArrayHasKey('Status', $response['headers']);
    }

    public function testValidOPTIONSRequest()
    {
        $methodName = 'OPTIONS';
        $url = 'http://api.duckduckgo.com/?q=DuckDuckGo&format=json&pretty=1';
        $params = array( 'track' => 'twitter' );
        $headers = $this->headers;

        $response = $this->client->request($methodName, $url, $params, $headers);

        $this->assertArrayHasKey('Protocol', $response['headers']);
        $this->assertArrayHasKey('Status', $response['headers']);
    }

    public function testValidCUSTOMRequest()
    {
        $methodName = 'SONRISACMS';
        $url = 'http://api.duckduckgo.com/?q=DuckDuckGo&format=json&pretty=1';
        $params = array( 'track' => 'twitter' );
        $headers = $this->headers;

        $response = $this->client->request($methodName, $url, $params, $headers);

        $this->assertArrayHasKey('Protocol', $response['headers']);
        $this->assertArrayHasKey('Status', $response['headers']);
    }

    public function tearDown()
    {
        $this->client = null;
    }
}
