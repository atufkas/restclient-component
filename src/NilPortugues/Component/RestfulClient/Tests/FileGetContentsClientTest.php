<?php
namespace NilPortugues\Component\RestfulClient\Tests;

class FileGetContentsClientTest extends \PHPUnit_Framework_TestCase
{
    protected $client;
    protected $params;
    protected $headers;

    public function setUp()
    {
        $this->client = new \NilPortugues\Component\RestfulClient\FileGetContentsClient();

        $this->params = array();
        $this->headers = array();
    }

    public function testInvalidURLRequest()
    {
        $methodName = 'GET';
        $url = '';
        $params = $this->params;
        $headers = $this->headers;

        $this->setExpectedException('\NilPortugues\Component\RestfulClient\Exceptions\RestfulClientException');
        $this->client->request($methodName,$url,$params,$headers);
    }

    public function testValidGETRequest()
    {
        $methodName = 'GET';
        $url = 'https://api.twitter.com/1.1/statuses/mentions_timeline.json';
        $params = array('count'=>2 );
        $headers = $this->headers;

        $response = $this->client->request($methodName,$url,$params,$headers);

        $this->assertArrayHasKey('Protocol',$response['headers']);
        $this->assertArrayHasKey('Status',$response['headers']);
    }

    public function testValidPOSTRequest()
    {
        $methodName = 'POST';
        $url = 'https://stream.twitter.com/1.1/statuses/filter.json';
        $params = array( 'track' => 'twitter' );
        $headers = $this->headers;

        $response = $this->client->request($methodName,$url,$params,$headers);

        $this->assertArrayHasKey('Protocol',$response['headers']);
        $this->assertArrayHasKey('Status',$response['headers']);
    }

    public function testValidPUTRequest()
    {
        $methodName = 'PUT';
        $url = 'https://stream.twitter.com/1.1/statuses/filter.json';
        $params = array( 'track' => 'twitter' );
        $headers = $this->headers;

        $response = $this->client->request($methodName,$url,$params,$headers);

        $this->assertArrayHasKey('Protocol',$response['headers']);
        $this->assertArrayHasKey('Status',$response['headers']);
    }

    public function testValidPATCHRequest()
    {
        $methodName = 'PATCH';
        $url = 'https://stream.twitter.com/1.1/statuses/filter.json';
        $params = array( 'track' => 'twitter' );
        $headers = $this->headers;

        $response = $this->client->request($methodName,$url,$params,$headers);

        $this->assertArrayHasKey('Protocol',$response['headers']);
        $this->assertArrayHasKey('Status',$response['headers']);
    }

    public function testValidDELETERequest()
    {
        $methodName = 'DELETE';
        $url = 'https://stream.twitter.com/1.1/statuses/filter.json';
        $params = array( 'track' => 'twitter' );
        $headers = $this->headers;

        $response = $this->client->request($methodName,$url,$params,$headers);

        $this->assertArrayHasKey('Protocol',$response['headers']);
        $this->assertArrayHasKey('Status',$response['headers']);
    }

    public function testValidHEADRequest()
    {
        $methodName = 'HEAD';
        $url = 'https://stream.twitter.com/1.1/statuses/filter.json';
        $params = array( 'track' => 'twitter' );
        $headers = $this->headers;

        $response = $this->client->request($methodName,$url,$params,$headers);

        $this->assertArrayHasKey('Protocol',$response['headers']);
        $this->assertArrayHasKey('Status',$response['headers']);
    }

    public function testValidOPTIONSRequest()
    {
        $methodName = 'OPTIONS';
        $url = 'https://stream.twitter.com/1.1/statuses/filter.json';
        $params = array( 'track' => 'twitter' );
        $headers = $this->headers;

        $response = $this->client->request($methodName,$url,$params,$headers);

        $this->assertArrayHasKey('Protocol',$response['headers']);
        $this->assertArrayHasKey('Status',$response['headers']);
    }

    public function testValidCUSTOMRequest()
    {
        $methodName = 'SONRISACMS';
        $url = 'https://stream.twitter.com/1.1/statuses/filter.json';
        $params = array( 'track' => 'twitter' );
        $headers = $this->headers;

        $response = $this->client->request($methodName,$url,$params,$headers);

        $this->assertArrayHasKey('Protocol',$response['headers']);
        $this->assertArrayHasKey('Status',$response['headers']);
    }

    public function tearDown()
    {
        $this->client = NULL;
    }

}
