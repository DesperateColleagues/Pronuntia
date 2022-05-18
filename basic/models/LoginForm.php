<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            ['email', 'email']
            // password is validated by validatePassword()
            //['password', 'validatePassword'],
        ];
    }

    public function login() {
        $resultRow = LogopedistaModel::find()
            ->where(['email' => $this->email, 'passwordD' => md5($this->password)])
            ->one();

        if($resultRow)
            return true;
        else
            return false;
    }

}
