<?php
namespace NilPortugues\Component\RestfulClient\Interfaces;

interface ClientInterface
{
    /**
     * @param  string $methodName
     * @param  string $url
     * @param  array  $params
     * @param  array  $headers
     * @return mixed
     */
    public function request($methodName,$url,array $params=array(),array $headers=array());
}
