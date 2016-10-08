<?php
/**
* classes core
* this classes are base classes becuase they have used inside most of other fucntions and classes
* @package cmf
* @subpackage v2
* @author Sina Salek <sina.salek.ws>
* @version $Id$
* @changes
* 	- ability to enabled disabled passing events to observers and command handlers, via setOption('observingEnabled',false) and setOption('commandingEnabled',false)
* @todo support cmfcException::isError() method
* @todo CMF_ERROR_RETURN and other raiseError constants should become CMF_
* 	
*/



if (!class_exists('cmfcExceptionStandAlone')) {
	trigger_error('cmfcExceptionStandAlone package does not included , please include it first',E_USER_ERROR);
}


define('CMF_ClassesCore_Ok',true,true);
define('CMF_ClassesCore_Error',2,true);

/**
 * all cmf classes inherit from this class
 *
 */
class cmfcClassesCoreStandAlone
{
	/**
	 * Log object used for error logging by ErrorStack
	 *
	 * @var    Log
	 * @access public
	 */
    var $_oLog = null;
	var $_version='$Id: classesCore.class.inc.php 663 2010-11-16 09:19:55Z salek $';
    
	/** 
	* @var cmfcStorage instance
	*/
	var $_oStorage=null;
	
	var $_observers=array();
	var $_observeringEnabled=true;
	var $_commandHandlers=array();
	var $_commandingEnabled=true;

	var $_dynamicSystemEnabled=false;
	var $_debugEnabled=false;
	var $_language=CMF_Ln_English;
	var $_options;
	var $_defaultError=CMF_Error;
	var $_messagesValue=array(
		CMF_ClassesCore_Ok		=> 'no error',
		CMF_ClassesCore_Error=>'unknown error'
	);
	var $_errorsStack=array();
		
	/**
	* @desc 
	*/
	//var $_propertiesValues
	

	/**
	 * there is no __construct function in php4 or down , so this function is solution , now it's possible 
	 * for all chid of this base class to have __construct functions
	 * 
	 */
	function cmfcClassesCore() {
		$args = func_get_args();
		if (is_callable(array(&$this, "__construct"))) {
			call_user_func_array(array(&$this, "__construct"), $args);
		}
	}

	function getVersion() {
		return $this->_version;
	}
	
	/**
	 * This function calculates the base location of package in filesystem and in browser
	 * This is a critical function for other packages to work properly
	 * By default it points to the real path when symlinks are used
	 * 
	 * Site with subdomain should set siteFolderPath manually and also pass siteFolderPathBrowser (http://domain.com) 
	 */
	function getCalculatedLocations($options,$parameters) {
		$server=$_SERVER;

		if (!isset($server['DOCUMENT_ROOT'])) {
			$server['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr(
				$server['SCRIPT_FILENAME'], 0, 0-strlen($server['PHP_SELF'])
			));
		}
		if (isset($server['DOCUMENT_ROOT'])) {
			if (strpos('ASD@#$@#$#23423'.dirname(__FILE__),'ASD@#$@#$#23423'.$server['DOCUMENT_ROOT'])===false) {
				$server['DOCUMENT_ROOT']='/chroot'.$server['DOCUMENT_ROOT'];
			}
		}
		if (!isset($options['pageFolderPath'])) {
			$options['pageFolderPath']=dirname($server['SCRIPT_FILENAME']);
            $options['pageFolderPath']=str_replace(array('/','\\'),DIRECTORY_SEPARATOR,$options['pageFolderPath']);
		}
		if (!isset($options['pageFolderPathBrowser'])) {
			$options['pageFolderPathBrowser']=dirname($server['SCRIPT_NAME']);
		}
		if (!isset($options['siteFolderPath'])) {
			$options['siteFolderPath']=$server['DOCUMENT_ROOT'];
			if (is_link($options['siteFolderPath'])) {
				$options['siteFolderPath']=readlink($options['siteFolderPath']);
			}
		} 
		if (!isset($options['siteFolderPathBrowser'])) {
			$options['siteFolderPathBrowser']='';
		}
		if (!isset($options['packageFolderPath'])) {
			$options['packageFolderPath']=dirname($parameters['filePath']);
            $options['siteFolderPath']=str_replace(array('/','\\'),DIRECTORY_SEPARATOR,$options['siteFolderPath']);
		}
		if (!isset($options['packageFolderPathBrowser'])) {
			$fileRelativePath=$options['siteFolderPathBrowser'].'/'.str_replace($options['siteFolderPath'],'',$options['packageFolderPath']);
			$options['packageFolderPathBrowser']=cmfcDirectory::normalizePath($fileRelativePath);
		}
		if (!isset($options['cmfFolderPath'])) {
			$options['cmfFolderPath']=realpath($options['packageFolderPath'].'/../../..');
            $options['cmfFolderPath']=str_replace(array('/','\\'),DIRECTORY_SEPARATOR,$options['cmfFolderPath']);
		}
		if (!isset($options['cmfFolderPathBrowser'])) {
			$fileRelativePath=$options['siteFolderPathBrowser'].'/'.str_replace($options['siteFolderPath'],'',$options['cmfFolderPath']);
			$options['cmfFolderPathBrowser']=cmfcDirectory::normalizePath($fileRelativePath);
		}
		
		return $options;
	}
	
	/**
	 * @NOTICE child classed definition should apply the byReference version of function
	 * to pass by reference to work correctly
	 * @NOTICE $options[$name] is require for PHP4,PHP5 compatibility
	 * @param $options
	 * @param $merge
	 * @return unknown_type
	 */
	function setOptions($options,$merge=false) {
		foreach ($options as $name=>$value) {
			$r=$this->setOption($name,$options[$name],$merge);
			if ($this->isError($r)) {
				return $r;
			}
		}
	}
	function setOptionsByReference(&$options,$merge=false) {
		foreach ($options as $name=>$value) {
			$r=$this->setOptionByReference($name,$options[$name],$merge);
			if ($this->isError($r)) {
				return $r;
			}
		}
	}
	
	/**
	 * @NOTICE child classed definition should apply the byReference version of function
	 * @param $name
	 * @param $value
	 * @param $merge
	 * @return unknown_type
	 */
	function setOption($name,$value,$merge=false) {
		return $this->setOptionByReference($name,$value,$merge);
	}	
	function setOptionByReference($name,&$value,$merge=false) {
		$r=null;
		if ($name=='storage') {
			$r=&$this->setStorage($value);
		} elseif ($name=='storage') {
			$r=&$this->setLog($value);
		} elseif (is_array($value) and $merge==true) {
			$this->{'_'.$name}=&cmfcArray::mergeRecursive($this->{'_'.$name},$value);
			$value=&$this->{'_'.$name};
		} else {
			$this->{'_'.$name}=&$value;
		}
		$this->_options[$name]=&$value;
		return $r;		
	}
	
	function setStorage(&$value) {
		$this->_oStorage=&$value; 
	}
	
	/**
	* works fine in both php4 & 5. but you should use & when you call the function. $b=&$ins->getOption('property')
	*/
	function &getOption($name) {
		return $this->{'_'.$name};
	}
	
	function setLog(&$value) {
		//if (!empty($this->_oLog))
			//$this->_oLog=&Log::singleton('file', 'out.log', 'CreativeMindFramework');
		$this->_oLog=&$value;
	}
	
	
	function getMessageValue($msgCode,$parameters=null) {
		if (isset($this->_messagesValue[$msgCode]))	
			$message=$this->_messagesValue[$msgCode];
		else
			$message=$this->_messagesValue[$this->_defaultError];
		if (is_array($parameters))
			$message=sprintf($message,$parameters);
		return $message;
	}
	
	
	/**
	*	fill all of object variables with their default values except $base_properties
	*	$base_properties=array('local_language_name','db','event_system','configurations','table_name_prefix');
	* 	@BENEFITS:increases object creation speed in loop, in fact there is no need to create class anymore, just create it at first time and then call this function for furture uses
	* 	@param $base_properties array
	*/
	function resetProperties($baseBroperties=null,$prefix=null)
	{
		$classVars=get_class_vars(cmfcPhp4::get_class($this));
		foreach ($classVars as $varName=>$defaultValue) {
			if (!in_array($varName,$baseBroperties)) {
				if (is_integer($defaultValue) or is_float($defaultValue))
					$this->{$prefix.$varName}=$defaultValue;
				else
					$this->{$prefix.$varName}=$defaultValue;
			}
		}
	}
	
	
	function arrayToProperties($propertiesValues,$exceptNulls=false,$prefix=null) {
		if (is_array($propertiesValues)) {
			if ($this->_dynamicSystemEnabled) {
				foreach($propertiesValues as $propertyName=>$propertyValue) {
					$this->{$prefix.$propertyName}=$propertyValue;
				}
			} else {
				// only for sample :
				/*
				if ($exceptNulls==false or ($exceptNulls and !is_null($propertiesValues[$this->colnId])))
					@$this->cvId=$propertiesValues[$this->colnId];
				*/
			}
			return true;
		}
		return false;
	}

	function propertiesToArray($exceptNulls=false,$prefix=null) {
		$propertiesValues=array();

		if ($this->_dynamicSystemEnabled) {			
			$vars=get_object_vars($this);
			foreach ($vars as $varName=>$varValue) {
				if (preg_match('/^'.$prefix.'.*/',$varName) or is_null($prefix))
					$propertiesValues[$varName]=$varValue;
			}
		} else {
			// only for sample :
			/*
			if ($exceptNulls==false or ($exceptNulls and !is_null($this->cvId)))
				$propertiesValues[$this->colnId]=$this->cvId;
			*/
		}

		return $propertiesValues;
	}

	function clearProperties($prefix=null)
	{
		if ($this->_dynamicSystemEnabled) {	
			$vars=get_object_vars($this);
			foreach ($vars as $varName=>$varValue) {
				if (preg_match('/^'.$prefix.'.*/',$varName) or is_null($prefix))
					$this->{$varName}=null;
			}
		} else {
			// only for sample :
			/*
			$this->_language=null;
			*/
		}
	}
	

    // }}}
    // {{{ raiseError()

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
		if (isset($this->_messagesValue[$code]) && empty($message))
			$message=$this->_messagesValue[$code];
			
		if (is_array($userinfo) && !empty($message)) {
			if (is_array($userinfo))
			foreach ($userinfo as $key=>$value) {
				$replacements['%'.$key.'%']=$value;
			}
			$message=cmfcString::replaceVariables($replacements,$message);
		}
		return cmfcExceptionStandAlone:: raiseError($message, $code, $mode, $options, $userinfo, $error_class, $skipmsg);
	}
	
	
	function isError($obj,$code=null) {
		return cmfcExceptionStandAlone::isError($obj,$code);
	}
	
	
    //! An accessor
    /**
    * Calls the update() function using the reference to each
    * registered observer - used by children of Observable
    * @return void
    */ 
    function notifyObservers ($event,$params=null) {
    	if ($this->_observeringEnabled==true)
    	if (is_array($this->_observers[$event]))
        foreach ($this->_observers[$event] as $observer) {
            call_user_func_array($observer,array(&$this,$params));
        }
    }
 
    //! An accessor
    /**
    * Register the reference to an object object
    * @param $observer array|string //like call_user_func first param
    * @return void
    */ 
    function addObserver($event, $observer,$parameters=null) {
       	$this->_observers[$event][]=$observer;
    }
    
    
    function prependObserver($event, $observer,$parameters=null) {
    	if (empty($this->_observers[$event])) $this->_observers[$event]=array();
    	array_unshift($this->_observers[$event],$observer);
    }
    
    function removeObservers($cmd) {
       	$this->_commandHandlers[$cmd]=array();
    }
	
	/**
	* @example
	* <code>
	* $this->runCommand('sendEmailAfterActivation',$columnsValues);
	* </code>
	*/
    function runCommand($cmd,&$params=null) {
    	if ($this->_commandingEnabled==true)
    	if (is_array($this->_commandHandlers[$cmd]))
        foreach ($this->_commandHandlers[$cmd] as $commandHandler) {
			$result=call_user_func_array($commandHandler,array(&$this,$cmd,$params));
            if (!cmfcExceptionStandAlone::isError($result)) {
				return $result;
			}
        }
    }
    
    function hasCommandHandler($cmd) {
       	if (is_array($this->_commandHandlers[$cmd]))
       		if (!empty($this->_commandHandlers[$cmd]))
       			return true;
       	return false;
    }
 
 	/**
 	* @example
 	* <code>
 	* $object->addCommandHandler('commandName','functionName')
 	* $object->addCommandHandler('commandName',array(&$myObject,'methodName'))
 	* </code>
 	*/
    function addCommandHandler ($cmd, $commandHandler,$parameters=null) {
       	$this->_commandHandlers[$cmd][]=$commandHandler;
    }
    
    
    function removeCommandHandlers ($cmd) {
       	$this->_commandHandlers[$cmd]=array();
    }
    
    function prependCommandHandler ($cmd, $commandHandler,$parameters=null) {
    	if (empty($this->_commandHandlers[$cmd])) $this->_commandHandlers[$cmd]=array();
    	array_unshift($this->_commandHandlers[$cmd],$commandHandler);
    }
    
    /**
    * memento design pattern
    * will clone the object for adding undo ability.
    * @todo
    * 	- should become complete 
    */
    function saveToMemento() {
    	return clone($this);
	}
	
    /**
    * memento design pattern
    * will load the object previous state
    * @todo
    * 	- should become complete
    */
    function restoreFromMemento($object) {
    	//commented duo to incopatiblility with php5
    	//$this=$object;
	}
}