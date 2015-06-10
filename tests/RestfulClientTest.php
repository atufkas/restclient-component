<?php
namespace Sonrisa\Test\Component\RestfulClient;

/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


class RestfulClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Sonrisa\Component\RestfulClient\RestfulClient $client
     */
    protected $client;

    public function setUp()
    {
        $this->client = new \Sonrisa\Component\RestfulClient\RestfulClient();
    }

    public function testSetHeader()
    {
        $useContentType = 'application/json; charset=utf-8';
        $this->client->setHeader('Content-Type', $useContentType);

        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty("headers");
        $property->setAccessible(true);

        $array = $property->getValue($this->client);
        $this->assertEquals($useContentType, $array['Content-Type']);
    }

    public function testSetHeaders()
    {
        $headers = array(
            'User-Agent' => 'Mozilla/5.0',
            'Origin' => 'http://my-lovely-host:3000'
        );

        $this->client->setHeaders($headers);

        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty("headers");
        $property->setAccessible(true);

        $array = $property->getValue($this->client);

        foreach ($headers as $key => $value) {
            $this->assertEquals($value, $array[$key]);
        }
    }

    public function testSetAcceptEncoding()
    {
        $this->client->setAcceptEncoding('gzip;q=1.0, identity; q=0.5, *;q=0');

        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty("headers");
        $property->setAccessible(true);

        $array = $property->getValue($this->client);
        $this->assertEquals('gzip;q=1.0, identity; q=0.5, *;q=0', $array['Accept-Encoding']);
    }

    public function testSetValidAcceptLanguage()
    {
        $this->client->setAcceptLanguage('en');

        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty("headers");
        $property->setAccessible(true);

        $array = $property->getValue($this->client);
        $this->assertEquals('en', $array['Accept-language']);
    }

    public function testSetInvalidAcceptLanguage()
    {
        $this->setExpectedException('\Sonrisa\Component\RestfulClient\Exceptions\RestfulClientException');
        $this->client->setAcceptLanguage('');
    }

    public function testSetUserAgent()
    {
        $this->client->setUserAgent('My Browser User Agent');

        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty("headers");
        $property->setAccessible(true);

        $array = $property->getValue($this->client);
        $this->assertEquals('My Browser User Agent', $array['User-Agent']);
    }

    public function testSetApiKey()
    {
        $this->client->setKey('apiKey', 'ThisIsMySecretApiKeyValue');

        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty("apiKey");
        $property->setAccessible(true);

        $array = $property->getValue($this->client);

        $this->assertArrayHasKey('apiKey', $array);
        $this->assertEquals('ThisIsMySecretApiKeyValue', $array['apiKey']);
    }

    public function testSetApiKeyIsSent()
    {
        $this->client->setKey('apiKey', 'ThisIsMySecretApiKeyValue');

        $url = 'http://www.google.cat';
        $params = array('hello' => 'world');

        $return = $this->client->get($url, $params);

        $this->assertArrayHasKey('request', $return);
        $this->assertArrayHasKey('URL', $return['request']);
        $this->assertTrue(strpos($return['request']['URL'], 'hello=world')!==false);
        $this->assertTrue(strpos($return['request']['URL'], 'apiKey=ThisIsMySecretApiKeyValue')!==false);
    }

    public function tearDown()
    {
        $this->client = null;
    }
}
