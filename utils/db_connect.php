<?php

/**
 * Abstract class for all connection objects. Each source system will
 * extend this class and define all neccessary connection parameters.
 * 
 * 
 * @author mzd2016
 *
 */
abstract class db_connect {
	
	public $isconnected = FALSE;
	protected $db = NULL;
	private $result;
	public $sql;
	protected $serverName;
	protected $username;
	protected $password;
	protected $database;
        private $server_connection;
	private $database_connection;
	
	/**
	 * Sets up connection parameters for the connection
	 * 
	 * @param source - identifier which dictates the database
	 *                 parameters to connect with
	 */
	public function __construct($source) {
		if ($source === "ARCH") {
			$this->serverName = Constants::$host [$source]["Server"];
			$this->username = Constants::$host [$source]["Username"];
			$this->password = Constants::$host [$source]["Password"];
			$this->database = Constants::$host [$source]["Database"];
			$this->db_type = Constants::$host [$source]["Type"];
		}
	}
	
	/*
	 * Returns the status of the connection
	 */
	public function getConnectionStatus() {
		return $this->isconnected;
	}
	
	// Subclasses must define how to connect, query, and close the database
	abstract public function connect($key);
	abstract public function query($stmt);
	abstract public function close();
}

/**
 * Connects to ARCH (vits-archsqlp02) as datasource for DDP
 * @author mzd2016
 *
 */
class cadc_db_connect extends db_connect {
	/**
	 * Passes arguments to the parent constructor and immediately
	 * initializes a connection
	 *
	 */
	public function __construct($source = "ARCH") {
		parent::__construct ("ARCH");
		$this->database_connection = $this->connect ( $source );
 		return $this->database_connection;
	}
	
	/**
	 * Connects to ARCH (vits-archsqlp02) as datasource for DDP
	 *
	 */
	public function connect($key) {	
		try {
			if ($key === "ARCH"){
				$conn = mssql_connect ( $this->serverName, $this->username, $this->password );
				
				if (!$conn){
					throw new Exception( 'There was a problem in connecting to ' . $key);
                                }
                                $this->server_connection = $conn;
				$selected = mssql_select_db ( $this->database, $conn );
					
				if (! $selected){
					throw new Exception( 'There was a problem in connecting to ' . $selected);
                                }
                                
				$this->isconnected = TRUE;
				return $selected;
			}
		} catch ( Exception $e ) {
			$this->isconnected = FALSE;
			die ( mssql_get_last_message () );
		}
	}
	
	/**
	 * Queries ARCH (vits-archsqlp02) and returns data
	 *
	 */
	public function query($stmt) {
		$result = mssql_query ( $stmt );
		
		if (! $result){
			die ( mssql_get_last_message () );
                }

		$row = mssql_fetch_assoc ( $result );
		return $row;
	}
	
	/**
	 * Closes the connection to a data source.
	 */
	public function close() {
		try {
                     $close = mssql_close ( $this->server_connection );
					
                     if (!$close){
			throw new Exception( 'There was an issue in disconnecting from ' . $this->serverName );
                     }
                     
                     $this->isconnected = FALSE;
                    
                     return $close;
		} catch ( Exception $e ) {
                        $this->isconnected = FALSE;
			die ( mssql_get_last_message () );
		}
	}
}
?>


