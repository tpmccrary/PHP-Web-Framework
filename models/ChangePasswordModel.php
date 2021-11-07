<?php

namespace app\models;

class ChangePasswordModel extends User
{
    // public string $oldPassword = '';
    public string $password = '';
    public string $passwordConfirm = '';

    public function rules(): array
    {
        return [
            'password' => [self::RULE_REQUIRED, 
            [
                self::RULE_MIN, 'min' => 3,
                self::RULE_MAX, 'max' => 20
            ]],
            'passwordConfirm' => [
                self::RULE_REQUIRED,
                [self::RULE_MATCH, "match" => "password"],
            ],
        ];
    }

    public function labels(): array
    {
        return [
            'password' => 'New Password',
            'passwordConfirm' => 'Confirm New Password',
        ];
    }

    public function attributes(): array
    {
        return [
            'password',
        ];
    }

    public function changePassword(): bool
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return $this->update();
    }
}