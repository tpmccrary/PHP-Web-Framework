<?php

namespace app\models;
require_once("../models/Model.php");
require_once("../models/DatabaseModel.php");
require_once("../models/UserModel.php");

class User extends UserModel
{
    const TYPE_ADMIN = "admin";
    const TYPE_USER = "user";

    public string $username = '';
    public string $password=  '';
    public string $passwordConfirm = '';
    public string $type = self::TYPE_USER;

    public function signup()
    {
        $this->type = self::TYPE_USER;
        // Password hashing implemented here.
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return $this->save();
    }

    public function rules(): array
    {
        return [
            "username" => [
                self::RULE_REQUIRED,
                self::RULE_USERNAME,
                [
                    self::RULE_MIN, 'min' => 3,
                    self::RULE_MAX, 'max' => 20
                ],
                [
                    self::RULE_UNIQUE, 'class' => self::class, 'attribute' => 'username'
                ]
            ],
            "password" => [
                self::RULE_REQUIRED,
                [
                    self::RULE_MIN, 'min' => 3,
                    self::RULE_MAX, 'max' => 20
                ]
            ],
            "passwordConfirm" => [
                self::RULE_REQUIRED,
                [self::RULE_MATCH, "match" => "password"],
            ],
        ];
    }

    public function tableName(): string
    {
        return "user";
    }

    public function attributes(): array
    {
        return [
            "username",
            "password",
            "type",
        ];
    }

    public function labels(): array
    {
        return [
            "username" => "Username",
            "password" => "Password",
            "passwordConfirm" => "Confirm Password",
        ];
    }

    public function primaryKey(): string
    {
        return "id";
    }

    public function getDisplayName(): string
    {
        return $this->username;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
