<?php

namespace app\core;

abstract class Migration
{
    protected $db;
    abstract public function up();
    abstract public function down();
}