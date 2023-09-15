<?php
namespace app\core;
abstract class Model{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique'; 
    public array $errors=[];
    public function loadData($data) {
        foreach ($data as $key => $value) {
            if(property_exists($this,$key)){
                $this->{$key} = $value;
            }
        }
    }
    abstract public function rules() : array ;
    
    public function validate() {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules  as $rule) {
                $ruleName = $rule;
                if(!is_string($ruleName)){
                    $ruleName = $rule[0];
                } 
                if($ruleName===self::RULE_REQUIRED && !$value){
                    $this->addErrorForRule($attribute,self::RULE_REQUIRED);
                }
                if($ruleName===self::RULE_EMAIL && !filter_var($value,FILTER_VALIDATE_EMAIL)){
                    $this->addErrorForRule($attribute,self::RULE_EMAIL);
                }
                if($ruleName===self::RULE_MIN && strlen($value)<$rule['min']){
                    $this->addErrorForRule($attribute,self::RULE_MIN,$rule);
                }
                if($ruleName===self::RULE_MAX && strlen($value)>$rule['max']){
                    $this->addErrorForRule($attribute,self::RULE_MAX,$rule);
                } 
                if($ruleName===self::RULE_MATCH && $value !== $this->{$rule['match']}){
                    $this->addErrorForRule($attribute,self::RULE_MATCH,$rule);
                } 
                if ($ruleName===self::RULE_UNIQUE) {
                    $className = $rule["class"];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr ;");
                    $statement->bindValue(":attr",$value);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if($record){
                        $this->addErrorForRule($attribute,self::RULE_UNIQUE,['field'=>$this->getLabel($attribute)]);
                    }
                }
            }
        }
        return empty($this->errors);
    }
    protected function addErrorForRule(string $attribute,string $rule,$params=[]) {
        $message = $this->errorMessages()[$rule]??'';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}",$value,$message);
        }
        $this->errors[$attribute][] = $message;

    }
    protected function addError(string $attribute,string $message) {
        $this->errors[$attribute][] = $message;

    }
    public function errorMessages() : array {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be valid email',
            self::RULE_MIN => 'Minimum length is {min}',
            self::RULE_MAX => 'Maximum length is {max}',
            self::RULE_MATCH => 'This field doesnt match the {match}',
            self::RULE_UNIQUE => 'Record with this {field} already exists'
        ];
    }
    public function hasError(string $attribute)  {
        return $this->errors[$attribute] ?? false;
    }
    public function getFirstError($attribute) : string {
        return $this->errors[$attribute][0] ?? '';
    }
    public function labels() : array {
        return [];
    }
    public function getLabel($attribute) {
        return $this->labels()[$attribute] ?? $attribute;
    }
}