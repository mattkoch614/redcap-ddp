<?php

class Constants
{
    //Key is REDCap pid. Value is path to the configuration file. ??? is also the pid.
    public static $pidfiles = array(
        "0000" => '/var/www/public/redcap-ddp/config/0000_config.json');

    // Key is a string to identify target database. Value is another associative
    // array where the keys are attributes for connecting to the server and the
    // values are the values.
    public static $host = array("TEST_DATABASE" => array("Server" => "test",
        "Username" => "test",
        "Password" => "test",
        "Database" => "DATABASE1",
        "Type" => "MSSQL")
    );

}