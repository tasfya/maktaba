<?php
/**
 * @internal
 */
if (!class_exists('cmfcException')) {
 
/**#@+
 * ERROR constants
 */
define('CMF_ERROR_RETURN',     1);
define('CMF_ERROR_PRINT',      2);
define('CMF_ERROR_TRIGGER',    4);
define('CMF_ERROR_DIE',        8);
define('CMF_ERROR_CALLBACK',  16);
 
// Backward Compatibility
if (!class_exists('Exception')) {
	class Exception {
	}
}

/**
 * Define a custom exception class
 * @ignore
 */
class cmfcExceptionStandAlone extends Exception {

    /**
     * raise an error
     * @example
     * <code>
     * 		return $this->raiseError('', CMF_Language_Error_Unknown_Short_Name,
	 *						CMF_ERROR_RETURN,NULL, 
	 *						array('shortName'=>$shortName)
	 *		);
     * </code>
     * @param string $msg  Error message
     * @param int    $code Error code
     * @access private
     */
	function raiseError($message = null, $code = null, $mode = null, $options = null,
                         $userinfo = null, $error_class = null, $skipmsg = false) {
		
		if ($code==CMF_ERROR_DIE) {
			echo $message;
			exit;
		}
		return new cmfcException($message,$code);
	}
	
	function isError($obj,$code=null) {
        if (strtolower(get_class($obj))==strtolower('cmfcExceptionStandAlone') || strtolower(get_class($obj))==strtolower('Exception') || strtolower(get_class($obj))=='pear_error') {
			return true;
		} else {
			return false;
		}
	}
}

}