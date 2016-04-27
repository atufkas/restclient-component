<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sonrisa\Component\RestfulClient\Interfaces;

interface ClientInterface
{
    /**
     * @param  string $methodName
     * @param  string $url
     * @param  array  $params
     * @param  array  $headers
     * @return mixed
     */
    public function request($methodName, $url, array $params=array(), array $headers=array());

    /**
     * @param int $timeout
     * @return RestfulClientInterface
     */
    public function setTimeout($timeout);
}
