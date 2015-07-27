<?php

/*
 * Tests correctness of metadata web service.
 */

while ( ! file_exists ( 'utils' ) )
	chdir ( '..' );

include_once 'utils/db_connect.php';
include_once 'utils/constants.php';
include_once 'exceptions/unsupportedprojectexception.php';
include_once 'fields/redcapfieldformatter.php';
include_once 'dao/ConfigDAO.php';
include_once 'fields/field_dict.php';

$project_id = "1073";
$meta = array();
	// Instantiate new ConfigDAO to hold information from configuration file
	$config = new ConfigDAO ( Constants::$pidfiles [$project_id] );
	$configarr = $config->getConfiguration ();
	foreach($configarr as $key => $value){
		foreach($value as $k=>$v){
		$meta[] = $v;
		}	
	}

echo json_encode($meta);
?>
