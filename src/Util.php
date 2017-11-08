<?php

namespace SmartMove;

/**
 * Class Util
 *
 * @package SmartMove
 */
class Util {

    /**
     * Converts a response from the SmartMove API to the corresponding PHP object.
     *
     * @param array $response The response from the SmartMove API.
     * @param array $opts
     * @return Resource|array
     */
    public static function convertToSmartMoveObject($data, $class) {
        if(self::isList($data)) { // i.e. a collection
            $mapped = [];
            foreach ($data as $i) {
                array_push($mapped, self::convertToSmartMoveObject($i, $class));
            }

            return $mapped;

        } elseif(is_array($data)) {
            if (!class_exists($class)) {
                $class = 'SmartMove\\Resource\\Resource';
            }

            return new $class($data);

        } else {
            return $data;
        }
    }

    /**
     * Whether the provided array (or other) is a list rather than a dictionary.
     *
     * @param array|mixed $array
     * @return boolean True if the given object is a list.
     */
    public static function isList($array) {
        if (!is_array($array)) {
            return false;
        }

        return count(array_filter(array_keys($array), 'is_string')) === 0;
    }

    /**
     * Helper method for manually constructing the URL with a query string
     * Parameters set by the transport will overwrite all query string values supplied in the URI of a request
     */
    public static function prepareQueryString($path, array $params = []) {
        if (!$params) {
            return $path;
        }

        $path .= (strpos($path, '?') === false) ? '?' : '&';
        $path .= http_build_query($params, '', '&');

        return $path;
    }
}
