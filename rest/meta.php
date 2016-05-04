<?php


/**
 * Presents all available fields in database to REDCap for mapping between
 * REDCap forms and a database.
 * 
 * @author     Marcos Davila (mzd2016@med.cornell.edu)
 * @since      v0.1
 * @package    rest
 * @license    Open Source
 * 
 */
class meta {
        // Tries to resolve missing class file dependencies at runtime
	function __autoload($className) {
		if (file_exists ( 'utils/' . $className . '.php' )) {
			require 'utils/' . $className . '.php';
			return true;
		} elseif (file_exists ( 'fields/' . $className . '.php' )) {
			require 'fields/' . $className . '.php';
			return true;
		} elseif (file_exists ( 'dao/' . $className . '.php' )) {
			require 'dao/' . $className . '.php';
			return true;
		} else {
			return false;
		}
	}

        /* 
         * Calls the autoloader to import required PHP files, then 
         * instantiates the field dictionary.
         */
	public function __construct($pid, $id, $fields, $config) {
		$registered = spl_autoload_register(array($this, '__autoload'));
                
                if (!$registered) {
                    throw new Exception ( 'The autoloader was unable to resolve a missing dependency.' );
                }
	}
	
        /**
         * Returns all eligible mapping fields from the configuration
         * files.
         */
	function meta($user, $project_id, $redcap_url) {
		
		if (in_array( $project_id , array_keys(Constants::$pidfiles)) ) {
			// Instantiate new ConfigDAO to hold information from configuration file
			$config = new ConfigDAO ( Constants::$pidfiles [$project_id] );
			$configarr = $config->getConfiguration ();
			$meta = array();
			
			// Loop through each entry in configarr and put value of key=>value
			// pair into the array			
			foreach($configarr as $key => $value){
				foreach($value as $k=>$v){
					$meta[] = $v;
				}
			}
			return json_encode($meta);
		}
		
	}
}
?>
