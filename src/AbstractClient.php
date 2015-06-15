<?php
/*
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sonrisa\Component\RestfulClient;

abstract class AbstractClient
{
    /**
     *
     * @param  mixed $data
     * @param  array $responseHeaders
     * @return type
     */
    protected function prepareResponse($data, array &$responseHeaders)
    {
        if (!empty($responseHeaders['Content-Type'])) {
            $type = explode(';', $responseHeaders['Content-Type']);
            $type = trim(strtolower($type[0]));

            switch ($type) {
                case 'application/json':
                    $data = Responses\JSONResponse::convert($data);

                    // NOTE:
                    //
                    // Next block causes a "Notice" and is quite confusing - probably
                    // a "typo for historical reasons" and might be removed completely soon:
                    //
                    // $data is response body data and therefore not a subject to be parsed
                    // for "header" fields. In case $responseHeaders was meant instead, it's
                    // still unclear why the status code should be added to the $data array
                    // which should only provide parsed data based on detected Content-Type
                    // as returned by this method.
                    //
                    // It's now put into an existence check instead of actually removing it
                    // to maintain BC compatibility for the unlikely case somebody used that
                    // as a "feature".
                    //
                    if (isset($data['headers']['Status'])) {
                        $status = explode(' ', $data['headers']['Status']);
                        $data['headers']['Status'] = $status[0];
                    }
                    break;

                case 'application/xml':
                    $data = Responses\XMLResponse::convert($data);

                    // NOTE: (see comment above for case "application/json")
                    if (isset($data['headers']['Status'])) {
                        $status = explode(' ', $data['headers']['Status']);
                        $data['headers']['Status'] = $status[0];
                    }
                    break;

                default:

                    //Check if is PHP serialized data
                    $errorReportingLevel = error_reporting();
                    error_reporting(0);
                    $unserialized = @unserialize($data);
                    if ($unserialized !== false) {
                        $data = $unserialized;
                    }
                    error_reporting($errorReportingLevel);
                    break;
            }
        }
        return $data;
    }
}
