<?php

use app\core\facades\DB;
use app\core\Migration;


class m0003_posts_migration extends Migration
{
    public function up()
    {
        //Posts table in SQL
        $SQL = "CREATE TABLE posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            body TEXT NOT NULL,
            user_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        ) ENGINE=INNODB;";
        DB::execute($SQL);
    }

    public function down()
    {
        $SQL = "DROP TABLE posts;";
        DB::execute($SQL);
    }
}