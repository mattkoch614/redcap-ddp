<?php

/**
 * Abstract class for all connection objects. Each source system will
 * extend this class and define all neccessary connection parameters.  
 *
 * @author mzd2016
 *
 */
abstract class mssql_db_connect
{
    protected $isconnected = FALSE;
    public $sql;
    protected $serverName;
    protected $username;
    protected $password;
    protected $database;
    protected $conn_id;

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
     */
    public function query($stmt)
    {
        $result = mssql_query($stmt, $this->conn_id);

        if (!$result) {
            echo ("Query to inspect: " . $stmt . '\n');
            die(mssql_get_last_message());
        }

        return $result;
    }

    // Subclasses must define how to connect and disconnect
    abstract public function connect($key);
    abstract public function close();
}

/**
 * Connects to ARCH (vits-archsqlp02) as datasource for DDP
 *
 * @author mzd2016
 *
 */
class arch_db_connect extends mssql_db_connect
{
    /**
     * Passes arguments to the parent constructor and immediately
     * initializes a connection
     */
    public function __construct($source = "ARCH")
    {
        parent::__construct("ARCH");
        $this->connect($source);
    }

    /**
     * Connects to ARCH (vits-archsqlp02) as datasource for DDP
     */
    public function connect($key)
    {
        try {
            if ($key === "ARCH") {
                $this->conn_id = mssql_connect($this->serverName, $this->username, $this->password);

                // See if any ETL is occurring. Unpack the result and fetch the first row.
                // The table it is looking at should only have one value in it called
                // IS_ETL_OCCURRING and it is brought in as an array so we reference only
                // the first element.
                $sql       = "SELECT IS_ETL_OCCURRING FROM SUPER_WEEKLY.dbo.ETL_STATUS;";
                $rslt      = $this->query($sql);
                $etlstatus = mssql_fetch_row($rslt);

                if ($etlstatus[0] === "Y") {
                    echo "Data is currently being updated at this time. Please try again later.";
                } else {

                    if (!$this->conn_id) {
                        throw new Exception('There was a problem in connecting to ' . $key);
                    }

                    $selected = mssql_select_db($this->database, $this->conn_id);

                    if (!$selected) {
                        echo 'There was a problem in connecting to ' . $selected;
                    }

                    $this->isconnected = TRUE;
                }
            }
        }
        catch (Exception $e) {
            $this->isconnected = FALSE;
            echo "Error caught in arch_db_connect: " . $e->getMessage();
            die(mssql_get_last_message());
        }
    }

    /**
     * Closes the connection to a data source.
     */
    public function close()
    {
        try {
            $close = mssql_close($this->conn_id);

            if (!$close) {
                echo 'There was an issue in disconnecting from ' . $this->serverName;
                throw new Exception('There was an issue in disconnecting from ' . $this->serverName);
            }

            $this->isconnected = FALSE;

            return $close;
        }
        catch (Exception $e) {
            $this->isconnected = FALSE;
            echo 'There was an issue in disconnecting from ' . $this->serverName;
            die(mssql_get_last_message());
        }
    }
}



/* Following the above pattern, this is a class
for the CREST database. (sep2131 2017-Feb) */
class crest_db_connect extends mssql_db_connect
{

    public function __construct($source = "CREST")
    {
        parent::__construct("CREST");
        $this->connect($source);
    }

    public function connect($key)
    {
        try {
            if ($key === "CREST") {
                $this->conn_id = mssql_connect($this->serverName, $this->username, $this->password);
                if (!$this->conn_id) {
                    throw new Exception('There was a problem in connecting to ' . $key);
                }
                $selected = mssql_select_db($this->database, $this->conn_id);

                if (!$selected) {
                    echo 'There was a problem in connecting to ' . $selected;
                }

                $this->isconnected = TRUE;
            }
        }
        catch (Exception $e) {
            $this->isconnected = FALSE;
            echo "Error caught in crest_db_connect: " . $e->getMessage();
            die(mssql_get_last_message());
        }
    }

    public function close()
    {
        try {
            $close = mssql_close($this->conn_id);

            if (!$close) {
                echo 'There was an issue in disconnecting from ' . $this->serverName;
                throw new Exception('There was an issue in disconnecting from ' . $this->serverName);
            }

            $this->isconnected = FALSE;

            return $close;
        }
        catch (Exception $e) {
            $this->isconnected = FALSE;
            echo 'There was an issue in disconnecting from ' . $this->serverName;
            die(mssql_get_last_message());
        }
    }
}

?>

