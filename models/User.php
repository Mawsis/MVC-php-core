<?php
namespace app\models;
use app\core\UserModel;

class User extends UserModel{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $confirmPassword = '';
    public int $status = self::STATUS_INACTIVE;
    public function save()  {
        $this->password = password_hash($this->password,PASSWORD_DEFAULT);
        $this->status = self::STATUS_INACTIVE;
        return parent::save();
    }
    public function rules() : array {
        return [
            'username'=>[self::RULE_REQUIRED],
            'email'=>[self::RULE_REQUIRED,self::RULE_EMAIL,[self::RULE_UNIQUE,'class'=>self::class]],
            'password'=>[self::RULE_REQUIRED,[self::RULE_MIN,'min'=>8]],
            'confirmPassword'=>[self::RULE_REQUIRED,[self::RULE_MATCH,'match'=>'password']],
        ];
    }
    public static function tableName():string {
        return 'users';
    }
    public static function attributes():array{
        return ['username','email','password','status'];
    }
    public static function primaryKey():string{
        return "id";
    }
    public function getDisplayName() : string {
        return $this->username;
    }
    public function labels():array {
        return [
            'confirmPassword'=>"Password Confirmation",
            'email'=>'Email'
        ];
    }
}