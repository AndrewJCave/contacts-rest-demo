<?php

/**
 * APICommon class for providing common routines
 * 
 * @author Andrew Cave andrew@wpdevhouse.com
 * @copyright (c) 2016, Andrew Cave
 */
class APICommon {
       
    /**
     * Encodes variables to JSON string
     *
     * @param mixed         $v value
     * @param bool          $success
     * @return string       encoded json string
     */
    public static function jsonEncode($v = array(), $success = true, $addSuccessMsg = true) {
        if ($addSuccessMsg) {
            $v['success'] = $success;
        }

        if (version_compare(PHP_VERSION, '5.2.0', '<')) {
            //require_once SPC_SYSPATH . '/libs/JSON.php';
            $j = new Services_JSON();
            $jsonRes = $j->encode($v);
	}

	$jsonRes = json_encode($v);

        //jsonp request
        //jQuery sends its unique callback function name and it calls this
        //callback function with the ajax success method
        if (isset($_GET['callback'])) {
            $callback = $_GET['callback'];
            $jsonRes = "$callback($jsonRes)";
        }

        return $jsonRes;
    }
    
    /**
     * Decodes JSON string into array
     *
     * @param mixed         $jsonStr encoded json string
     * @param bool          $success
     * @return array        decoded json string data
     */
    public static function jsonDecode($jsonStr = "") {
        return json_decode($jsonStr, true);        
    }
    
    
    /**
     * Logs the contents of an array.
     * 
     * @param type $array
     */
    public static function log_array($array = array()) {
//        foreach ($array as $value) {
//            error_log($value);
//        }
        foreach ($array as $key => $value) {
            if (! is_array($value)) {
                error_log("$key -> $value");
            } else {
                error_log("$key");
                foreach ($array[$key] as $key2 => $value2) {
                    error_log("Key :: $key2 -> " . $value2);
                }
            }
             
         }
    }
}
