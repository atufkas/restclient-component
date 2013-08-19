<?php
namespace NilPortugues\Component\RestfulClient\Tests;

class RestfulClientTest extends \PHPUnit_Framework_TestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = new \NilPortugues\Component\RestfulClient\RestfulClient();
    }

    public function testSetAcceptEncoding()
    {
        $this->client->setAcceptEncoding('gzip;q=1.0, identity; q=0.5, *;q=0');

        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty("headers");
        $property->setAccessible(true);

        $array = $property->getValue($this->client);
        $this->assertEquals('gzip;q=1.0, identity; q=0.5, *;q=0',$array['Accept-Encoding']);
    }

    public function testSetValidAcceptLanguage()
    {
        $this->client->setAcceptLanguage('en');

        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty("headers");
        $property->setAccessible(true);

        $array = $property->getValue($this->client);
        $this->assertEquals('en',$array['Accept-language']);
    }

    public function testSetInvalidAcceptLanguage()
    {
        $this->setExpectedException('\NilPortugues\Component\RestfulClient\Exceptions\RestfulClientException');
        $this->client->setAcceptLanguage('');
    }

    public function testSetUserAgent()
    {
        $this->client->setUserAgent('My Browser User Agent');

        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty("headers");
        $property->setAccessible(true);

        $array = $property->getValue($this->client);
        $this->assertEquals('My Browser User Agent',$array['User-Agent']);
    }

    public function testSetApiKey()
    {
        $this->client->setKey('apiKey','ThisIsMySecretApiKeyValue');

        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty("apiKey");
        $property->setAccessible(true);

        $array = $property->getValue($this->client);

        $this->assertArrayHasKey('apiKey',$array);
        $this->assertEquals('ThisIsMySecretApiKeyValue',$array['apiKey']);
    }

    public function testSetApiKeyIsSent()
    {
        $this->client->setKey('apiKey','ThisIsMySecretApiKeyValue');

        $url = 'http://www.google.com';
        $params = array('hello' => 'world');

        $return = $this->client->get($url,$params);

        $this->assertTrue(strpos($return['request']['Referer'],'hello=world')!==false);
        $this->assertTrue(strpos($return['request']['Referer'],'apiKey=ThisIsMySecretApiKeyValue')!==false);
    }

    public function tearDown()
    {
        $this->client = NULL;
    }
}
