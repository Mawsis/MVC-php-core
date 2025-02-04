<?php

namespace app\core;

abstract class UserModel extends DbModel
{
    public abstract function getDisplayName(): string;
    public ?int $id;
}
