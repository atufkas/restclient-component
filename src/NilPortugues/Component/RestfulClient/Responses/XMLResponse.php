<?php
namespace NilPortugues\Component\RestfulClient\Responses;

class XMLResponse extends RestfulResponseInterface
{
    /**
     * @param  string $response
     * @return array
     */
    public static function convert($response)
    {
        $xml = new \XMLReader();

        if ( $xml->XML($response, NULL, LIBXML_DTDVALID) ) {
            $xmlList = simplexml_load_string($response);

            $data = array();
            foreach ($xmlList as $element) {
                static::convertXmlObjToArr($element, $data);
            }

            return $data;
        } else {
            return $response;
        }
    }

    /**
     * Parse a SimpleXMLElement object recursively into an Array.
     *
     * @param  \SimpleXMLElement $obj
     * @param  type              $arr Target array where the values will be stored
     * @return type
     */
    protected static function convertXmlObjToArr( \SimpleXMLElement $obj, &$arr )
    {
        $children = $obj->children();
        $executed = false;
        foreach ($children as $index => $node) {
            if ( array_key_exists( $index, (array) $arr ) ) {
                if (array_key_exists( 0, $arr[$index] ) ) {
                    $i = count($arr[$index]);
                    static::convertXmlObjToArr($node, $arr[$index][$i]);
                } else {
                    $tmp = $arr[$index];
                    $arr[$index] = array();
                    $arr[$index][0] = $tmp;
                    $i = count($arr[$index]);
                    static::convertXmlObjToArr($node, $arr[$index][$i]);
                }
            } else {
                $arr[$index] = array();
                static::convertXmlObjToArr($node, $arr[$index]);
            }

            $attributes = $node->attributes();
            if ( count($attributes) > 0 ) {
                $arr[$index]['@attributes'] = array();
                foreach ($attributes as $attr_name => $attr_value) {
                    $attr_index = strtolower(trim((string) $attr_name));
                    $arr[$index]['@attributes'][$attr_index] = trim((string) $attr_value);
                }
            }

            $executed = true;
        }
        if ( !$executed && $children->getName() == "" ) {
            settype($obj, 'String');
            $arr = $obj;
        }

        return;
    }
}
