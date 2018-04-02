<?php namespace Tests\Unit;

use fake_db2_connect;
use PHPUnit\Framework\TestCase;

include_once('./utils/Constants.php');
include_once('./utils/db2_db_connect.php');
include_once('./utils/fake_db2_connect.php');

class db2_db_connectTest extends TestCase
{
    /**
     * @test
     */
    public function can_instantiate_new_instance()
    {
        $connector = new fake_db2_connect();
        $this->assertTrue($connector->getConnectionStatus());
    }
}