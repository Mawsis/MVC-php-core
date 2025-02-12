<?php

use app\core\Application;
use app\core\facades\DB;
use app\core\Migration;

class m0002_add_password_column extends Migration
{
    public function up()
    {
        DB::execute("ALTER TABLE users ADD COLUMN password VARCHAR(255) NOT NULL ;");
    }
    public function down()
    {
        DB::execute("ALTER TABLE users DROP COLUMN password ;");
    }
}