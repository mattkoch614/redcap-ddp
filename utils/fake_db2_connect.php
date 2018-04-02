<?php

/**
 * A fake DB2 database connection implementation
 *
 * @author Matthew Koch
 *
 */
class fake_db2_connect extends db2_db_connect
{
    /**
     * Passes arguments to the parent constructor and immediately
     * initializes a connection
     * @param string $source
     */
    public function __construct($source = "TEST_DATABASE")
    {
        $this->serverName = Constants::$host[$source]["Server"];
        $this->username   = Constants::$host[$source]["Username"];
        $this->password   = Constants::$host[$source]["Password"];
        $this->database   = Constants::$host[$source]["Database"];
        $this->db_type    = Constants::$host[$source]["Type"];
        $this->connect($source);
    }

    public function connect($key)
    {
        try {
            if ($key === "TEST_DATABASE") {

                $conn_string = "DRIVER={IBM DB2 ODBC DRIVER};DATABASE=$this->database;" .
                    "HOSTNAME=$this->serverName;PORT=$this->port;PROTOCOL=TCPIP;UID=$this->username;PWD=$this->password;";

                $this->conn_id = $conn_string;


                if (!$this->conn_id) {
                    throw new Exception('There was a problem in connecting to ' . $key);
                }


                $this->isconnected = TRUE;
            }
        }
        catch (Exception $e) {
            $this->isconnected = FALSE;
            echo "Error caught in fake_db2_connect: " . $e->getMessage();
            die();
        }
    }

    public function close()
    {
        try {
            $close = true;

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
            die();
        }
    }

}