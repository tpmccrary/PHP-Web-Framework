<?php

namespace app\models;

use app\core\Application;
use app\models\Model;

class SignInForm extends Model
{
    public string $username = '';
    public string $password = '';

    public function rules(): array
    {
        return [
            'username' => [self::RULE_REQUIRED, self::RULE_USERNAME],
            'password' => [self::RULE_REQUIRED],
        ];
    }

    public function signin()
    {
        $user = User::findOne(['username' => $this->username]);

        if (!$user)
        {
            $this->addError('username', 'No account found with given username.');
            return false;
        }
        
        if (!password_verify($this->password, $user->password))
        {
            $this->addError('password', 'Incorrect password.');
            return false;
        }


        return Application::$app->signin($user);
    }

    public function labels(): array
    {
        return [
            'username' => 'Username',
            'password' => 'Password',
        ];
    }

}