<?php

/**
 * Abstract class for connecting to a DB2 instance.
 *
 * In order to connect, you'll need to install the IBM DB2 driver.
 * http://php.net/manual/en/ibm-db2.installation.php#113330
 */
abstract class db2_db_connect
{
    protected $isconnected = FALSE;
    public $sql;
    protected $serverName;
    protected $username;
    protected $password;
    protected $database;
    protected $conn_id;
    protected $port;

    /**
     * Sets up connection parameters for the connection
     *
     * @param
     *            source - identifier which dictates the database
     *            parameters to connect with
     */
    public function __construct($source)
    {
        $this->serverName = Constants::$host[$source]["Server"];
        $this->username   = Constants::$host[$source]["Username"];
        $this->password   = Constants::$host[$source]["Password"];
        $this->database   = Constants::$host[$source]["Database"];
        $this->db_type    = Constants::$host[$source]["Type"];
        $this->port       = Constants::$host[$source]["Port"];
    }

    /*
     * Returns the status of the connection
     */
    public function getConnectionStatus()
    {
        return $this->isconnected;
    }

    /**
     * Queries a data source
     * @param $stmt
     * @return mixed|resource
     */
    public function query($stmt)
    {

        $prepared = db2_prepare($this->conn_id, $stmt);
        if ($prepared) {
            $result = db2_execute($prepared);

            if (!$result) {
                echo ("Query to inspect: " . $stmt . '\n');
                die(db2_stmt_errormsg($prepared));
            }

            return $prepared;

        }

    }

    // Subclasses must define how to connect and disconnect
    abstract public function connect($key);
    abstract public function close();

}