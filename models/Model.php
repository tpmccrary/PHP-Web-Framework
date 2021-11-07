<?php

namespace app\models;

use app\core\Application;

abstract class Model
{

    public const RULE_REQUIRED = 'required';
    public const RULE_USERNAME = 'username';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    public array $errors = [];

    abstract public function rules(): array;

    public function loadData($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function validate()
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};

            foreach ($rules as $rule) {
                $ruleName = $rule;

                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName == self::RULE_REQUIRED && !$value) {
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName == self::RULE_USERNAME && !preg_match('/^[a-zA-Z0-9]/', $value)) {
                    $this->addErrorForRule($attribute, self::RULE_USERNAME);
                }
                if ($ruleName == self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if ($ruleName == self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }
                if ($ruleName == self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                }
                if ($ruleName == self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniquteAttribute = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();

                    $sql = "SELECT * FROM $tableName WHERE $uniquteAttribute = :attr";

                    $stmnt = Application::$app->database->prepare($sql);

                    $stmnt->bindValue(":attr", $value);
                    $stmnt->execute();
                    $record = $stmnt->fetchObject();

                    if ($record) {
                        $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
                    }
                }
            }
        }

        return empty($this->errors);
    }

    private function addErrorForRule(string $attribute, string $rule, $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function addError(string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }

    public function errorMessages()
    {
        return [
            self::RULE_REQUIRED => 'Required field',
            self::RULE_USERNAME => 'Must be valid username',
            self::RULE_MIN => 'Minimum length must be {min}',
            self::RULE_MAX => 'Maximum length must be {min}',
            self::RULE_MATCH => 'This filed must match {match}',
            self::RULE_UNIQUE => 'This {field} is already taken',
        ];
    }

    public function hasError(string $attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getErrors(string $attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }

    public function labels(): array
    {
        return [];
    }

    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }
}
