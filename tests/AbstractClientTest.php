<?php
namespace Sonrisa\Test\Component\RestfulClient;
use Sonrisa\Component\RestfulClient\Interfaces\RestfulClientInterface;

/**
 * Author:  Nil Portugués Calderó <contact@nilportugues.com>
 * Author:  Matthias Lienau <matthias@mlienau.de
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


abstract class AbstractClientTest extends \PHPUnit_Framework_TestCase
{
    /** @var RestfulClientInterface $client */
    protected $client;
    protected $params;
    protected $headers;

    public function setUp()
    {
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
        $params = array('count' => 2);
        $headers = $this->headers;

        $response = $this->client->request($methodName, $url, $params, $headers);

        $this->assertArrayHasKey('Protocol', $response['headers']);
        $this->assertArrayHasKey('Status', $response['headers']);
    }

    public function testValidGETRequestWithJsonResponse()
    {
        $methodName = 'GET';
        $url = 'http://echo.jsontest.com/foo/hello/bar/42';
        $params = array();
        $headers = $this->headers;

        $response = $this->client->request($methodName, $url, $params, $headers);

        // Assert that response actually returns header "Content-Type: application/json" and...
        $ctParts = explode(';', $response[ 'headers' ][ 'Content-Type' ]);
        $this->assertEquals('application/json', trim($ctParts[ 0 ]));

        // ... that that JSON is correctly pre-parsed into array
        $this->assertArrayHasKey('foo', $response[ 'response' ]);
        $this->assertArrayHasKey('bar', $response[ 'response' ]);
    }

    public function testValidGETRequestWithCustomHeader()
    {
        $methodName = 'GET';
        $url = 'https://httpbin.org/get?show_env=1';
        $params = array();
        $headers = array_merge($this->headers, array(
            'Authorization' => 'Bearer this1is2not3really4a5valid6token',
            'Content-Type' => 'application/json'
        ));

        $response = $this->client->request($methodName, $url, $params, $headers);

        // Assert that current request response _echoes_ headers like requested
        foreach ($headers as $key => $value) {
            $this->assertArrayHasKey($key, $response[ 'response' ][ 'headers' ]);
        }
    }

    public function testValidPOSTRequest()
    {
        $methodName = 'POST';
        $url = 'https://httpbin.org/post';
        $testData = array('foo' => array('bar' => 'baz'));
        $params = $testData;
        $headers = array_merge($this->headers, array(
            'Content-Type' => 'application/json'
        ));

        $response = $this->client->request($methodName, $url, $params, $headers);

        $this->assertArrayHasKey('Protocol', $response['headers']);
        $this->assertArrayHasKey('Status', $response['headers']);

        $this->assertEquals(200, $response['headers']['Status']);
        $this->assertEquals($testData, $response['response']['json']);
    }

    public function testValidPUTRequest()
    {
        $methodName = 'PUT';
        $url = 'https://httpbin.org/put';
        $testData = array('foo' => array('bar' => 'baz'));
        $params = $testData;
        $headers = array_merge($this->headers, array(
            'Content-Type' => 'application/json'
        ));

        $response = $this->client->request($methodName, $url, $params, $headers);

        $this->assertArrayHasKey('Protocol', $response['headers']);
        $this->assertArrayHasKey('Status', $response['headers']);

        $this->assertEquals(200, $response['headers']['Status']);
        $this->assertEquals($testData, $response['response']['json']);
    }

    public function testValidPATCHRequest()
    {
        $methodName = 'PATCH';
        $url = 'https://httpbin.org/patch';
        $testData = array('foo' => array('bar' => 'baz'));
        $params = $testData;
        $headers = array_merge($this->headers, array(
            'Content-Type' => 'application/json'
        ));

        $response = $this->client->request($methodName, $url, $params, $headers);

        $this->assertArrayHasKey('Protocol', $response['headers']);
        $this->assertArrayHasKey('Status', $response['headers']);

        $this->assertEquals(200, $response['headers']['Status']);
        $this->assertEquals($testData, $response['response']['json']);
    }

    public function testValidDELETERequest()
    {
        $methodName = 'DELETE';
        $url = 'https://httpbin.org/delete';
        $testData = array('foo' => array('bar' => 'baz'));
        $params = $testData;
        $headers = array_merge($this->headers, array(
            'Content-Type' => 'application/json'
        ));

        $response = $this->client->request($methodName, $url, $params, $headers);

        $this->assertArrayHasKey('Protocol', $response['headers']);
        $this->assertArrayHasKey('Status', $response['headers']);

        $this->assertEquals(200, $response['headers']['Status']);
        $this->assertEquals($testData, $response['response']['json']);
    }

    public function testValidOPTIONSRequest()
    {
        $methodName = 'OPTIONS';
        $url = 'http://api.duckduckgo.com/?q=DuckDuckGo&format=json&pretty=1';
        $params = array();
        $headers = $this->headers;

        $response = $this->client->request($methodName, $url, $params, $headers);

        $this->assertArrayHasKey('Protocol', $response['headers']);
        $this->assertArrayHasKey('Status', $response['headers']);

        // "Not Allowed" is what duckduckgo currently returns for OPTIONS
        $this->assertEquals(405, $response['headers']['Status']);
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

        // "Not Allowed" is what duckduckgo currently returns for unknown http methods
        $this->assertEquals(405, $response['headers']['Status']);
    }

    public function tearDown()
    {
        $this->client = null;
    }
}
