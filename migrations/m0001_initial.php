<?php

use app\core\Application;
use app\core\facades\DB;
use app\core\Migration;

class m0001_initial extends Migration
{
    public function up()
    {
        $SQL = "CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR (255) NOT NULL,
                username VARCHAR (255) NOT NULL,
                status TINYINT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE = INNODB;";
        DB::execute($SQL);
    }
    public function down()
    {
        $SQL = "DROP TABLE users;";
        DB::exec($SQL);
    }
}