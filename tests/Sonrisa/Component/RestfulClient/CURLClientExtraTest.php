<?php
/*
 * Author: Matthias Lienau <matthias@mlienau.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Class CURLClientExtraTest
 *
 * Test some extra cases for the curl client implementation that assures
 * that responses with content-type "application/json" are pre-parsed as expected.
 */
class CURLClientExtraTest extends \PHPUnit_Framework_TestCase
{
    protected $client;
    protected $params;
    protected $headers;

    public function setUp()
    {
        $this->client = new \Sonrisa\Component\RestfulClient\CURLClient();

        $this->params = array();
        $this->headers = array();
    }

    /**
     * Check GET verb request on endpoint of "jsontest.com" which should
     * return a Content-Type "application/json" and therefore triggers
     * the JSON decode transformation to an array - whose result is tested as well
     *
     * @Test
     */
    public function testValidGETWithJsonContentTypeRequest()
    {
        $methodName = 'GET';
        $url = 'http://echo.jsontest.com/foo/hello/bar/42';
        $params = array();
        $headers = $this->headers;

        $response = $this->client->request($methodName,$url,$params,$headers);

        // Assert that current request response actually returns "application/json"
        // as Content-Type (otherweise adjust testing endpoint!)
        $ctParts = explode(';',$response[ 'headers' ][ 'Content-Type' ]);
        $this->assertEquals('application/json',trim($ctParts[ 0 ]));

        // Assert that JSON is correctly pre-parsed into array
        $this->assertArrayHasKey('foo',$response[ 'response' ]);
        $this->assertArrayHasKey('bar',$response[ 'response' ]);

    }

    public function tearDown()
    {
        $this->client = NULL;
    }
}
