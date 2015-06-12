<?php
namespace Sonrisa\Test\Component\RestfulClient;

/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


class CURLClientTest extends AbstractClientTest
{
    public function setUp()
    {
        $this->client = new \Sonrisa\Component\RestfulClient\CURLClient();
        parent::setUp();
    }
}
