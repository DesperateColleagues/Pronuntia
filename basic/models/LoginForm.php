<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user = false;

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
            ['email', 'email'],
            // password is validated by validatePassword()
            ['password', 'validatePassword']
        ];
    }

    public function login() {
        if ($this->validate()){
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }

        return false;
    }

    public function getUser(){
        if ($this->_user === false){
            $this->_user = LogopedistaModel::findByEmail($this->email);
        }

        return $this->_user;
    }

    public function validatePassword($attribute, $params){
        if (!$this->hasErrors()){
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)){
                $this->addError($attribute, 'Email e password non corretti');
            }
        }
    }

}
