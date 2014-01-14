<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sonrisa\Component\RestfulClient\Responses;

interface RestfulResponseInterface
{
    /**
     *
     * @param  mixed                                                                  $response
     * @return array
     * @throws Sonrisa\Component\RestfulClient\Exceptions\RestfulClientException
     */
     public static function convert($response);
}
